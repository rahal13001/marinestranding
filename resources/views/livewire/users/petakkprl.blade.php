<?php

use Livewire\Volt\Component;
use Mary\Traits\Toast;
use App\Models\Kkprl\Kkprlmap;
use App\Models\Kkprl\Kkprluse;
use App\Models\Kkprl\Zone;
use App\Models\Stranding\Province;
use Illuminate\Support\Collection;

new class extends Component {
    use Toast;
    
    public string $search = '';
    public bool $drawer = false;
    public bool $rooms = true;
    public bool $rooms_use = false;
    public array $province_id = [];
    public array $zone_id = [];


     // Clear filters
     public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    public function kkprlMaps(): Collection
    {
        return Kkprlmap::with('zone', 'province', 'regulation')
        ->when($this->province_id, function ($query) {
            $query->whereIn('province_id', $this->province_id);
        })
        ->when($this->zone_id, function ($query) {
            $query->whereIn('zone_id', $this->zone_id);
        })    
        ->get();
    }

    public function kkprlUses(): Collection
    {
        return Kkprluse::with('province')->when($this->province_id, function ($query) {
            $query->whereIn('province_id', $this->province_id);
        })->get();
    }

    public function provinces(): Collection
    {
        return Province::get();
    }

    public function zones(): Collection
    {
        return Zone::get();
    }

    public function with(): array
    {
        //untuk filter periksa apakah kkprl dinyalakan, jika ya tampilkan
        $kkprlusefiltered = $this->rooms_use ? $this->kkprlUses() : collect([]);
        //untuk filter penggunaan ruang periksa apakah peta tata ruang digunakan jika ya tampilkan
        $kkprlmapfiltered = $this->rooms ? $this->kkprlMaps() : collect([]); // Condition based on rooms
        $this->dispatch('kkprlmapUpdated', $kkprlmapfiltered);
        $this->dispatch('kkprluseUpdated', $kkprlusefiltered);
        return [
            'kkprlmaps' => $kkprlmapfiltered,
            'kkprluses' => $kkprlusefiltered,
            'provinces' => $this->provinces(),
            'zones' => $this->zones(),
        ];
    }


}; ?>

<div>


    <x-header title="Web GIS Informasi Ruang Laut" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <x-card>
        <div id="map" class="h-[75vh] z-40 relative" wire:ignore></div>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3 z-50 relative">
        <div class="mt-3">
            <x-toggle label="KKPRL" wire:model.live="rooms_use" right hint="Display Peta KKPRL" />
            <hr />
        </div>

        <div class="mt-3">
            <x-toggle label="Ruang Laut" wire:model.live="rooms" right hint="Display Peta Ruang Laut" />
            <hr />  
        </div>
        
        <div class="mt-5">
            <x-choices
                label="Pilih Provinsi"
                wire:model.live="province_id"
                :options="$provinces"
                option-label="province"
                icon="o-map-pin"
                height="max-h-96"
                searchable
            />
        </div>
        @if ($rooms)
            <div class="mt-3">
                <x-choices
                    label="Pilih Zona"
                    wire:model.live="zone_id"
                    :options="$zones"
                    option-label="zone_name"
                    icon="o-map-pin"
                    height="max-h-96"
                    searchable
                />
            </div>
        @endif


        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        let map;
        let layers = [];
        let kkprldatas = [];

        document.addEventListener('livewire:initialized', function () {
            map = L.map('map').setView([-2.50, 132.1908], 6);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                 // Check if $rooms is true and display the map
                 if (@json($rooms)) {
                    displayMap(@json($kkprlmaps));                
                }              

                Livewire.on('kkprlmapUpdated', kkprlmaps => {    
                    updatedMap(kkprlmaps);   
                });

             
                //nanti if ada disini untuk display dan sembunyikan peta pemanfaatan ruang laut
                if (@json($rooms_use)) {
                    displayKkprluse(@json($kkprluses));
                }

                Livewire.on('kkprluseUpdated', kkprluses => {
                    updatedKkprluse(kkprluses);
                })
    
        });


        async function displayMap(kkprlmaps) {
            if (!kkprlmaps.length) return;
            for (const kkprlmap of kkprlmaps) {
                const response = await fetch('storage/' + kkprlmap.shp);
                const geojsonFeature = await response.json();
                const style = {
                    "color": kkprlmap.color,
                    "weight": 5,
                    "opacity": 0.65
                };
                const layer = L.geoJSON(geojsonFeature, {
                    style: style,
                    onEachFeature: function (feature, layer) {
                        layer.on('click', function(e) {
                            // Extract the clicked coordinates
                            const clickedLatLng = e.latlng;
                            const kawasan = kkprlmap.zone.namakawasan ? `<p class="text-md font-bold tracking-tight text-gray-900 dark:gray-400">Nama Kawasan : <i>${kkprlmap.zone.namakawasan}</i></p>` : '';

                            // Update the popup content with the clicked coordinates
                            let popupContent = `
                                <container class="p-4 max-w-sm mx-auto bg-white rounded-lg dark:bg-gray-800 sm:max-w-sm">
                                    <p class="text-md font-bold tracking-tight text-gray-900 dark:gray-400">Zona : <i>${kkprlmap.zone.zone_name}</i></p>
                                    ${kawasan}
                                    <p class="text-gray-700 dark:text-gray-400">Di Provinsi ${kkprlmap.province.province}
                                        <br>
                                        Dasar Hukum ${kkprlmap.regulation.regulation_number} tentang ${kkprlmap.regulation.regulation_name}
                                        
                                    </p>

                                    <p class="text-xs tracking-tight text-gray-900 dark:gray-400">Koordinat klik lat : ${clickedLatLng.lat}, lon : ${clickedLatLng.lng}</p>
                                </container>`;

                            // Bind and open the popup with the updated content
                            layer.bindPopup(popupContent).openPopup();
                        });
                    }
                }).addTo(map);

                layers.push(layer);
            }
        }
        async function updatedMap(kkprlmaps){
            layers.forEach(layer => {
                map.removeLayer(layer);
            });
            layers = [];
            if (!kkprlmaps.length) return;

            for (const kkprlmap of kkprlmaps) {
                for (const kkprl of kkprlmap){
                    const response1 = await fetch('storage/' + kkprl.shp);
                    const kawasan = kkprl.zone.namakawasan ? `<p class="text-md font-bold tracking-tight text-gray-900 dark:gray-400">Nama Kawasan : <i>${kkprl.zone.namakawasan}</i></p>` : '';
                    const geojsonFeature1 = await response1.json();
                    const style = {
                        "color": kkprl.color,
                        "weight": 2,
                        "opacity": 0.65
                    };
                    const layer = L.geoJSON(geojsonFeature1, {
                        style: style,
                        onEachFeature: function (feature, layer) {
                            layer.on('click', function(e) {
                                // Extract the clicked coordinates
                                const clickedLatLng = e.latlng;

                                // Update the popup content with the clicked coordinates
                                let popupContent = `
                                    <container class="p-4 max-w-sm mx-auto bg-white rounded-lg dark:bg-gray-800 sm:max-w-sm">
                                        <p class="text-md font-bold tracking-tight text-gray-900 dark:gray-400">Zona : <i>${kkprl.zone.zone_name}</i></p>
                                        ${kawasan}
                                        <p class="text-gray-700 dark:text-gray-400">Di Provinsi ${kkprl.province.province}
                                            <br>
                                            Dasar Hukum ${kkprl.regulation.regulation_number} tentang ${kkprl.regulation.regulation_name}
                                            <br>
                                            
                                        </p>

                                        <p class="text-xs tracking-tight text-gray-900 dark:gray-400">Koordinat klik latitude : ${clickedLatLng.lat}, longitude : ${clickedLatLng.lng} </p>
                                    </container>`;

                                // Bind and open the popup with the updated content
                                layer.bindPopup(popupContent).openPopup();
                            });
                        }
                    }).addTo(map);

                    layers.push(layer);
                }
            }
        }

        
        async function displayKkprluse(kkprluses) {
    if (!kkprluses.length) return;
    
    for (const kkprluse of kkprluses) {
            const response = await fetch('storage/' + kkprluse.subject_shp);
            const geojsonFeature = await response.json();
            const style = {
                "color": kkprluse.color,
                "weight": 2,
                "opacity": 0.75
            };

            const layerdata = L.geoJSON(geojsonFeature, {
                style: style,
                onEachFeature: function (feature, layer) {
                    layer.on('click', function (e) {
                        const latlng = e.latlng;

                        layer.bindPopup(
                            `<container class="p-4 max-w-sm mx-auto bg-white rounded-lg dark:bg-gray-800 sm:max-w-sm">
                                <table class="table-fixed w-full">
                                        <tbody>
                                            <tr>
                                                <td class="font-bold text-gray-700 dark:text-gray-400">Subjek Hukum</td>
                                                <td class="text-gray-700 dark:text-gray-400">: ${kkprluse.subject_name}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold text-gray-700 dark:text-gray-400">Aktivitas:</td>
                                                <td class="text-gray-700 dark:text-gray-400">: ${kkprluse.subject_activity}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold text-gray-700 dark:text-gray-400">Lokasi:</td>
                                                <td class="text-gray-700 dark:text-gray-400">: ${kkprluse.province.province}</td>
                                            </tr>
                                            <tr>
                                                <td class="font-bold text-gray-700 dark:text-gray-400">Tipe KKPRL:</td>
                                                <td class="text-gray-700 dark:text-gray-400">: ${kkprluse.subject_status}</td>
                                            </tr>
                                            ${kkprluse.width != null ? `
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Luas/Panjang</td>
                                                    <td class="text-gray-700 dark:text-gray-400">: ${kkprluse.width} Ha / ${kkprluse.length} Km</td>
                                                </tr>
                                            ` : ''}
                                        </tbody>
                                    </table>
                                    <p class="text-gray-700 dark:text-gray-400 text-xs">Koordinat: Latitude: ${latlng.lat}, Longitude: ${latlng.lng}</p>
                            </container>`
                        ).openPopup();
                    });
                }
            }).addTo(map);

            kkprldatas.push(layerdata);
        }
    }

        async function updatedKkprluse(kkprluses){

                kkprldatas.forEach(kkprldata => {
                    map.removeLayer(kkprldata);
                });
                kkprldatas = [];

                if (!kkprluses.length) return;

                
                
            for (const kkprluse of kkprluses) {
                for (const kkprl of kkprluse){
                    const response1 = await fetch('storage/' + kkprl.subject_shp);
                    const geojsonFeature1 = await response1.json();
                    const style = {
                        "color": kkprl.color,
                        "weight": 2,
                        "opacity": 0.75
                    };

                    
                    const layerdata = L.geoJSON(geojsonFeature1, {
                        style: style,
                        onEachFeature: function (feature, layer) {
                            layer.on('click', function(e) {
                                // Extract the clicked coordinates
                                const clickedLatLng = e.latlng;

                                 // Determine width and length values
                                const width = kkprl.width ? `${kkprl.width} Ha` : '-';
                                const length = kkprl.length ? `${kkprl.length} Km` : '-';
                               
                                
                                // Update the popup content with the clicked coordinates
                                let popupContent = `
                                    <container class="p-4 max-w-sm mx-auto bg-white rounded-lg dark:bg-gray-800 sm:max-w-sm">
                                        <table class="table-fixed w-full">
                                            <tbody>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Subjek Hukum</td>
                                                    <td class="text-gray-700 dark:text-gray-400">: ${kkprl.subject_name}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Aktivitas</td>
                                                    <td class="text-gray-700 dark:text-gray-400">: ${kkprl.subject_activity}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Lokasi</td>
                                                    <td class="text-gray-700 dark:text-gray-400">: ${kkprl.province.province}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Jenis KKPRL</td>
                                                    <td class="text-gray-700 dark:text-gray-400">: ${kkprl.subject_status}</td>
                                                </tr>
                                               <tr>
                                                <td class="font-bold text-gray-700 dark:text-gray-400">Luas / Panjang</td>
                                                <td class="text-gray-700 dark:text-gray-400">: ${width} / ${length}</td>
                                            </tr>
                                             
                                            </tbody>
                                        </table>
                                        <p class="text-gray-700 dark:text-gray-400 text-xs">Koordinat area yang di klik lat : ${clickedLatLng.lat}, lon : ${clickedLatLng.lng}</p>
                                    </container>`;
                                
                                // Bind and open the popup with the updated content
                                layer.bindPopup(popupContent).openPopup();
                            });
                        }
                    }).addTo(map);
                    
                    kkprldatas.push(layerdata);
                }
            }
        }
        
    </script>


</div>
