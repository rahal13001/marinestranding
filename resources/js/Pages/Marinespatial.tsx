import React, { useEffect, useRef, useState } from "react";
import "ol/ol.css";
import Map from "ol/Map";
import View from "ol/View";
import TileLayer from "ol/layer/Tile";
import OSM from "ol/source/OSM";
import VectorLayer from "ol/layer/Vector";
import VectorSource from "ol/source/Vector";
import GeoJSON from "ol/format/GeoJSON";
import { Fill, Stroke, Style } from "ol/style";
import Overlay from "ol/Overlay";
import { Head } from "@inertiajs/react";
import KkprlFilter from "@/components/KkprlFilter";
import { router } from "@inertiajs/react";


interface MarineSpatialPlanningProps {
  kkprlmaps: any[];
  kkprluses: any[];
  provinces: any[];
  zones: any[];
  subjectStatuses: any[];
  selectedFilters: FilterData;
}

interface FilterData {
  province: string[];       // ✅ Must be string[]
  zone: string[];           // ✅ Must be string[]
  subject_status: string[]; // ✅ Must be string[]
  showKkprlMaps: boolean;
  showKkprlUses: boolean;
}

const MarineSpatialPlanning: React.FC<MarineSpatialPlanningProps> = ({
  kkprlmaps,
  kkprluses,
  provinces,
  zones,
  subjectStatuses,
  selectedFilters
  

  }) => {
  const mapRef = useRef<HTMLDivElement>(null);
  const popupRef = useRef<HTMLDivElement>(null);

  

  useEffect(() => {
    if (!mapRef.current || !popupRef.current) return;

    const map = new Map({
      target: mapRef.current,
      layers: [
        new TileLayer({
          source: new OSM(),
        }),
      ],
      view: new View({
        center: [13500000, -200000],
        zoom: 5,
      }),
    });
      // ✅ Fix: Define a function to close the popup
    const closePopup = () => {
      popup.setPosition(undefined);
      popupRef.current!.style.display = "none";
    };

    (window as any).closePopup = closePopup;

    // Popup Overlay
    const popup = new Overlay({
      element: popupRef.current,
      positioning: "bottom-center",
      stopEvent: false,
      offset: [0, -10],
    });
    map.addOverlay(popup);

    /** Function to Add KKPRL Maps Layer **/
      if (selectedFilters.showKkprlMaps) {
        const addKkprlMapsLayer = (item: any) => {
          const vectorSource = new VectorSource({
            url: item.shp,
            format: new GeoJSON(),
          });
    
          const vectorLayer = new VectorLayer({
            source: vectorSource,
            style: new Style({
              fill: new Fill({ color: `${item.color}80` }),
              stroke: new Stroke({ color: item.color, width: 2 }),
            }),
          });
    
          vectorSource.once("change", () => {
            vectorSource.getFeatures().forEach((feature) => {
              feature.setProperties({
                layerLabel: "kkprlmaps",
                zone: item.zone,
                kawasan: item.kawasan,
                regulation: item.regulation,
                province: item.province,
              });
            });
          });
        
          map.addLayer(vectorLayer);
      };

      /** Load KKPRL Maps **/
      kkprlmaps.forEach(addKkprlMapsLayer);
    }

    if (selectedFilters.showKkprlUses) {
          /** Function to Add KKPRL Uses Layer **/
        const addKkprlUsesLayer = (item: any) => {
          const vectorSource = new VectorSource({
            url: item.subject_shp,
            format: new GeoJSON(),
          });

          const vectorLayer = new VectorLayer({
            source: vectorSource,
            style: new Style({
              fill: new Fill({ color: `${item.color}80` }),
              stroke: new Stroke({ color: item.color, width: 2 }),
            }),
          });

          vectorSource.once("change", () => {
            vectorSource.getFeatures().forEach((feature) => {
              feature.setProperties({
                layerLabel: "kkprluses",
                province_use: item.province_use,
                subject_activity: item.subject_activity,
                subject_status: item.subject_status,
                width: item.width,
                length: item.length,
              });
            });
          });
          
        
          map.addLayer(vectorLayer);
        };

        /** Load KKPRL Uses **/
        kkprluses.forEach(addKkprlUsesLayer);
    }
    

    /** Click Event for Popup **/
    map.on("singleclick", (event) => {
      const feature = map.forEachFeatureAtPixel(event.pixel, (feat) => feat);
      if (feature) {
        const label = feature.get("layerLabel");
        popup.setPosition(event.coordinate);
        popupRef.current!.style.display = "block";
    
        // Detect Dark Mode
        const isDarkMode = document.documentElement.classList.contains("dark");
        popupRef.current!.className = `absolute p-4 rounded-lg shadow-xl border w-72 ${
          isDarkMode ? "bg-gray-900 text-white border-gray-700" : "bg-white text-black border-gray-300"
        }`;
    
        if (label === "kkprlmaps") {
          popupRef.current!.innerHTML = `
            <div class="popup-content text-sm">
              <h2 class="text-md font-semibold border-b pb-2 mb-2 flex items-center">
                📑 Informasi Tata Ruang Laut
              </h2>
              <div class="grid grid-cols-[20px_90px_auto] gap-1 items-center">
                <span>📍</span> <strong>Zone</strong> <span>: ${feature.get("zone") || "-"}</span>
                <span>🏞️</span> <strong>Kawasan</strong> <span>: ${feature.get("kawasan") || "-"}</span>
                <span>📜</span> <strong>Regulasi</strong> <span>: ${feature.get("regulation") || "-"}</span>
                <span>📌</span> <strong>Provinsi</strong> <span>: ${feature.get("province") || "-"}</span>
              </div>
              <button onclick="closePopup()" class="w-full bg-red-600 text-white py-2 mt-4 rounded-lg hover:bg-red-700 transition">Close</button>
            </div>`;
        } else if (label === "kkprluses") {
          const length = feature.get("length") ? `${feature.get("length")} Km` : "- Km";
          const width = feature.get("width") ? `${feature.get("width")} Ha` : "- Ha";
    
          popupRef.current!.innerHTML = `
            <div class="popup-content text-sm">
              <h2 class="text-md font-semibold border-b pb-2 mb-2 flex items-center">
                📑 Informasi KKPRL
              </h2>
              <div class="grid grid-cols-[20px_90px_auto] gap-1 items-center">
                <span>🌍</span> <strong>Provinsi</strong> <span>: ${feature.get("province_use") || "-"}</span>
                <span>⚒️</span> <strong>Aktivitas</strong> <span>: ${feature.get("subject_activity") || "-"}</span>
                <span>🏗️</span> <strong>Bentuk KKPRL</strong> <span>: ${feature.get("subject_status") || "-"}</span>
                <span>📏</span> <strong>Luas / Panjang</strong> <span>: ${length} / ${width}</span>
              </div>
              <button onclick="closePopup()" class="w-full bg-red-600 text-white py-2 mt-4 rounded-lg hover:bg-red-700 transition">Close</button>
            </div>`;
        }
      } else {
        popup.setPosition(undefined);
        popupRef.current!.style.display = "none";
      }
    });
    return () => map.setTarget(undefined);
  }, [kkprlmaps, kkprluses, selectedFilters]);
 
  return (
    <>
      <Head title="Marine Spatial Planning" />
        <div className="p-2">
          <div className="flex justify-between items-center mb-1">
            <KkprlFilter
              provinces={provinces}
              zones={zones}
              subjectStatuses={subjectStatuses}
              selectedFilters={selectedFilters}
            />
          </div>

          <div className="relative">
            <div ref={mapRef} className="w-full h-[70vh] border border-gray-300 dark:border-gray-700 shadow-md" />
            <div ref={popupRef} className="absolute bg-white p-2 rounded shadow-md hidden" />
          </div>
        </div>
    </>
  );
};

export default MarineSpatialPlanning;
