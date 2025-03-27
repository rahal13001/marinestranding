import React, { useEffect, useRef, useState } from "react";
import "ol/ol.css";
import Map from "ol/Map";
import View from "ol/View";
import TileLayer from "ol/layer/Tile";
import OSM from "ol/source/OSM";
import { fromLonLat } from "ol/proj";
import VectorLayer from "ol/layer/Vector";
import VectorSource from "ol/source/Vector";
import Feature from "ol/Feature";
import Point from "ol/geom/Point";
import Style from "ol/style/Style";
import Icon from "ol/style/Icon";
import Overlay from "ol/Overlay";
import { Head } from "@inertiajs/react";
import { Button } from "@/components/ui/button";
import { Drawer, DrawerContent, DrawerTitle, DrawerDescription } from "@/components/ui/drawer";
import { Filter } from "lucide-react";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { ScrollArea } from "@/components/ui/scroll-area";
import Select from "react-select";
import "/resources/css/react-select.css";
import { route } from "ziggy-js";
import { router } from "@inertiajs/react";
import { SingleValue } from "react-select";




interface MapData {
  id: number;
  latitude: number;
  longitude: number;
  information_date: string;
  location: string;
  province: string | null;
  icon: string | null;
  species: string | null;

}

interface SelectOption {
  value: string;
  label: string;
}

interface FilterData {
  province: SelectOption[];
  species: SelectOption[];
  group: SelectOption[];
  startYear: SingleValue<SelectOption>;
  endYear: SingleValue<SelectOption>;
}

export default function Home({
    maps,
    provinces = [],
    species = [],
    groups = [],
    years = [],
    selectedFilters = { province: [], species: [], group: [], startYear: null, endYear: null }
 }: {
    maps: MapData[],
    provinces: { id: string, province: string }[],
    species: { id: string, species: string }[],
    groups: { id: string, group_name: string }[],
    years: string[],
    selectedFilters: { province: string[], species: string[], group: string[], startYear: string | null, endYear: string | null }
  }) {
  const mapRef = useRef<HTMLDivElement | null>(null);
  const popupRef = useRef<HTMLDivElement | null>(null);
  const popupOverlayRef = useRef<Overlay | null>(null);
  const [isOpen, setIsOpen] = useState(false);

  const provinceOptions: SelectOption[] = (provinces || []).map((p) => ({
    value: p.id.toString(),
    label: p.province,
  }));
  
  const speciesOptions: SelectOption[] = (species || []).map((s) => ({
    value: s.id.toString(),
    label: s.species,
  }));
  
  const groupOptions: SelectOption[] = (groups || []).map((g) => ({
    value: g.id.toString(),
    label: g.group_name,
  }));
  
  const yearOptions: SelectOption[] = (years || []).map((year) => ({
    value: year.toString(),
    label: year.toString(),
  }));
  
  
  const [values, setValues] = useState<FilterData>({
    province: provinceOptions.filter((p) => selectedFilters.province.includes(p.value)), 
    species: speciesOptions.filter((s) => selectedFilters.species.includes(s.value)), 
    group: groupOptions.filter((g) => selectedFilters.group.includes(g.value)), 
    startYear: yearOptions.find((y) => y.value === selectedFilters.startYear) || null, 
    endYear: yearOptions.find((y) => y.value === selectedFilters.endYear) || null, 
  });

  const resetFilters = (e: React.MouseEvent<HTMLButtonElement>) => {
    e.preventDefault(); // ✅ Prevent default form behavior
  
    // ✅ Ensure filters reset first, then send the request
    setValues({
      province: [],
      species: [],
      group: [],
      startYear: null,
      endYear: null,
    });
  
    // ✅ Use a slight delay to ensure state updates before making the request
    setTimeout(() => {
      router.post("/", {
        province: [],
        species: [],
        group: [],
        startYear: null,
        endYear: null,
      });
    }, 0); // 0ms delay ensures next event loop cycle executes with the updated state
  };
 

  function handleSubmit(e: React.MouseEvent<HTMLButtonElement>) {
    e.preventDefault();
    router.post('/', {
      ...values,
      province: values.province.map((p) => p.value), // ✅ Convert to flat array
      species: values.species.map((s) => s.value),   // ✅ Convert to flat array
      group: values.group.map((g) => g.value),       // ✅ Convert to flat array
      startYear: values.startYear ? values.startYear.value : null, // ✅ Ensure single value
      endYear: values.endYear ? values.endYear.value : null,       // ✅ Ensure single value
    });
  }

  useEffect(() => {
    if (!mapRef.current) return;

    const map = new Map({
      target: mapRef.current,
      layers: [
        new TileLayer({
          source: new OSM(),
        }),
      ],
      view: new View({
        center: fromLonLat([131.25, -2.75]), 
        zoom: 6,
      }),
    });

    const vectorSource = new VectorSource();
    const vectorLayer = new VectorLayer({ source: vectorSource });
    map.addLayer(vectorLayer);

    // --- Add Markers ---
    maps.forEach((data) => {
      const marker = new Feature({
        geometry: new Point(fromLonLat([data.longitude, data.latitude])),
      });

      // Attach data to marker for reference
      marker.setProperties({
        id: data.id,
        latitude: data.latitude,
        longitude: data.longitude,
        information_date: data.information_date,
        location: data.location,
        province: data.province,
        species : data.species
      });

      // Marker Style
      marker.setStyle(
        new Style({
          image: new Icon({
            anchor: [0.5, 1],
            scale: 0.03,
            src: data.icon || "/img/default-marker.png",
          }),
        })
      );

      vectorSource.addFeature(marker);
    });

    if (popupRef.current) {
      popupOverlayRef.current = new Overlay({
        element: popupRef.current,
        positioning: "bottom-center",
        stopEvent: true,
        offset: [0, -15],
      });
      map.addOverlay(popupOverlayRef.current);
    }

 // --- Click Event Listener ---
map.on("singleclick", (event) => {
  const clickedFeatures = map.getFeaturesAtPixel(event.pixel);

  if (clickedFeatures.length > 0 && popupOverlayRef.current && popupRef.current) {
    const feature = clickedFeatures[0];

    // Get data from marker properties
    const data = feature.getProperties();

    if (data && data.id) {
      const coordinate = event.coordinate;

      // Detect Dark Mode
      const isDarkMode = document.documentElement.classList.contains("dark");

      // Set class-based dark mode for better compatibility
      popupRef.current.classList.toggle("dark-mode-popup", isDarkMode);
      popupRef.current.classList.toggle("light-mode-popup", !isDarkMode);

      popupRef.current.innerHTML = `
        <div class="popup-content">
          <strong>🐋 Spesies: ${data.species}</strong><br>
          📍 <b>Provinsi:</b> ${data.province || "Unknown"}<br>
          📌 <b>Lokasi:</b> ${data.location}<br>
          🗺️ <b>Lat:</b> ${data.latitude}, <b>Long:</b> ${data.longitude}<br>
          📆 <b>Tanggal:</b> ${data.information_date}<br>

          <button id="close-popup">Close</button>
        </div>
      `;

      popupOverlayRef.current.setPosition(coordinate);
      popupRef.current.style.display = "block";

      // Add event listener for closing the popup
      setTimeout(() => {
        const closeButton = document.getElementById("close-popup");
        if (closeButton) {
          closeButton.addEventListener("click", () => {
            popupOverlayRef.current?.setPosition(undefined);
            popupRef.current!.style.display = "none";
          });
        }
      }, 50);
    }
  } else {
    popupOverlayRef.current?.setPosition(undefined);
    if (popupRef.current) popupRef.current.style.display = "none";
  }
});

    return () => map.setTarget(undefined);
  }, [maps]);

  

  return (
    <div>
      <Head title="Marine Life Stranding Map" />

    {/* Filter Button - Responsive */}
    <div className="absolute right-[45px] top-[158px] sm:top-[40px] md:top-[118px] md:right-13">
            <Button 
              variant="outline" 
              onClick={() => setIsOpen(true)} 
              className="flex items-center gap-2 shadow-md px-3 py-2 text-sm md:text-base"
            >
              <Filter className="w-4 h-4" /> Filters
            </Button>
          </div>
         
       {/* Sidebar Drawer - Open from Right */}
       <Drawer 
          open={isOpen} 
          onOpenChange={(open) => {
            setIsOpen(open);
            if (!open) {
              setTimeout(() => {
                const activeElement = document.activeElement as HTMLElement | null;
                if (activeElement && typeof activeElement.blur === "function") {
                  activeElement.blur(); // ✅ Ensures blur() is only called if it exists
                }
              }, 10);
            }
          }} 
          direction="right"
        >
        <DrawerContent className="w-full md:w-80 max-w-md bg-white dark:bg-gray-900 shadow-lg transform transition-transform ease-in-out duration-300 border-l border-gray-300 dark:border-gray-700 flex flex-col">
          {/* ✅ Add Dialog Title & Description for Accessibility */}
          <DrawerTitle className="p-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
            Filters
          </DrawerTitle>
          <DrawerDescription className="p-4 text-gray-600 dark:text-gray-300">
            Gunakan Filter Untuk Menyaring Data
          </DrawerDescription>

          <ScrollArea className="p-4 text-gray-600 dark:text-gray-300 flex-grow">
            <div className="space-y-4">
            <Label id="province-label">Provinsi</Label>
              <Select<SelectOption, true>
                aria-labelledby="province-label" // ✅ Use aria-labelledby instead of htmlFor
                isMulti
                options={provinceOptions}
                value={values.province}
                onChange={(selected) => setValues({ ...values, province: [...selected] })}
                classNamePrefix="react-select"
                placeholder="Cari Provinsi..."
              />

              <Label id="species-label">Spesies</Label>
              <Select<SelectOption, true>
                aria-labelledby="species-label" // ✅ Use aria-labelledby instead of htmlFor
                isMulti
                options={speciesOptions}
                value={values.species}
                onChange={(selected) => setValues({ ...values, species: [...selected] })}
                classNamePrefix="react-select"
                placeholder="Cari Spesies..."
              />

              <Label id="group-label">Kelompok Biota</Label>
              <Select<SelectOption, true>
                aria-labelledby="group-label" // ✅ Use aria-labelledby instead of htmlFor
                isMulti
                options={groupOptions}
                value={values.group}
                onChange={(selected) => setValues({ ...values, group: [...selected] })}
                classNamePrefix="react-select"
                placeholder="Cari Kelompok Biota..."
              />

              <Label id="startYear-label">Tahun Mulai</Label>
              <Select<SelectOption, false>
                aria-labelledby="startYear-label" // ✅ Use aria-labelledby instead of htmlFor
                isClearable
                options={yearOptions}
                value={values.startYear}
                onChange={(selected) => setValues({ ...values, startYear: selected || null })}
                classNamePrefix="react-select"
                placeholder="Pilih Tahun Mulai..."
              />

              <Label id="endYear-label">Tahun Akhir</Label>
              <Select<SelectOption, false>
                aria-labelledby="endYear-label" // ✅ Use aria-labelledby instead of htmlFor
                isClearable
                options={yearOptions}
                value={values.endYear}
                onChange={(selected) => setValues({ ...values, endYear: selected || null })}
                classNamePrefix="react-select"
                placeholder="Pilih Tahun Akhir..."
              />
            </div>
            </ScrollArea>

            <div className="p-4 border-t dark:border-gray-700 bg-white dark:bg-gray-900 flex justify-between gap-2 w-full">
              <Button onClick={handleSubmit} className="flex-1">Terapkan</Button>
              <Button variant="outline" onClick={resetFilters} className="flex-1">Reset</Button>
            </div>
            </DrawerContent>
            </Drawer>
  
      

      <div ref={mapRef} className="w-full h-[70vh] border border-gray-300 dark:border-gray-700 shadow-md" />
      <div ref={popupRef} className="absolute bg-white p-4 border border-gray-400 shadow-lg rounded-md text-md" style={{ display: "none" }} />
    </div>
  );
}
