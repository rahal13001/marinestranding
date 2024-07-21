<?php

use App\Models\User;
use App\Models\Stranding\Map;
use App\Models\Stranding\Province;
use App\Models\Stranding\Species;
use App\Models\Stranding\Group;
use Illuminate\Support\Collection;
use Livewire\Volt\Component;
use Mary\Traits\Toast;
use Carbon\Carbon;

new class extends Component {
    use Toast;
    


    public string $search = '';
    public bool $drawer = false;
    public array $province_id = [];
    public array $species_id = [];
    public array $group_id = [];
    public string $start_date = '';
    public string $end_date = '';
    public array $sortBy = ['column' => 'name', 'direction' => 'asc'];

    // Clear filters
    public function clear(): void
    {
        $this->reset();
        $this->success('Filters cleared.', position: 'toast-bottom');
    }

    // Delete action
    public function delete($id): void
    {
        $this->warning("Will delete #$id", 'It is fake.', position: 'toast-bottom');
    }

    public function strandings(): Collection
    {
        return Map::with('category', 'group', 'species', 'province')
            // ->whereHas('group', function ($query) {
            //     $query->whereNotNull('icon');
            // })
            // ->whereNotNull('latitude')
            // ->whereNotNull('longitude')
            ->when($this->province_id, function ($query) {
                $query->whereIn('province_id', $this->province_id);
            })
            ->when($this->start_date, function ($query) {
                $query->whereDate('information_date', '>=', $this->start_date);
            })
            ->when($this->end_date, function ($query) {
                $query->whereDate('information_date', '<=', $this->end_date);
            })
            ->when($this->species_id, function ($query) {
                $query->whereIn('species_id', $this->species_id);
            })
            ->when($this->group_id, function ($query) {
                $query->whereIn('group_id', $this->group_id);
            })
            ->get();
    }

    public function provinces(): Collection
    {
        return Province::get();
    }

    public function species(): Collection
    {
        return Species::get();
    }

    public function group(): Collection
    {
        return Group::get();
    }

    public function with(): array
    {
        $strandingsfiltered = $this->strandings();
        $this->dispatch('strandingsUpdated', $strandingsfiltered); 
        return [
            'strandings' => $this->strandings(),
            'provinces' => $this->provinces(),
            'species' => $this->species(),
            'group' => $this->group(),
            
        ];
    }
};
?>

<div>

    <!-- HEADER -->
    <x-header title="Web GIS Biota Laut Terdampar" separator progress-indicator>
        <x-slot:actions>
            <x-button label="Filters" @click="$wire.drawer = true" responsive icon="o-funnel" />
        </x-slot:actions>
    </x-header>

    <!-- TABLE  -->
    <x-card>
        <div id="map" class="h-[75vh] z-40 relative" wire:ignore></div>
    </x-card>

    <!-- FILTER DRAWER -->
    <x-drawer wire:model="drawer" title="Filters" right separator with-close-button class="lg:w-1/3 z-50 relative">
        <div class="mt-3">
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
        <div class="mt-3">
            <x-choices
                label="Pilih spesies"
                wire:model.live="species_id"
                :options="$species"
                option-label="species"
                icon="o-map-pin"
                height="max-h-96"
                searchable
            />
        </div>
        <div class="mt-3">
            <x-choices
                label="Pilih Kelompok Biota"
                wire:model.live="group_id"
                :options="$group"
                option-label="group_name"
                icon="o-map-pin"
                height="max-h-96"
                searchable
            />
        </div>
        <div class="mt-3">
            <x-datetime
                label="Tanggal Mulai"
                wire:model.live="start_date"
                icon="o-calendar"
            />
        </div>
        <div class="mt-3">
            <x-datetime
                label="Tanggal Selesai"
                wire:model.live="end_date"
                icon="o-calendar"
            />
        </div>
        <x-slot:actions>
            <x-button label="Reset" icon="o-x-mark" wire:click="clear" spinner />
            <x-button label="Done" icon="o-check" class="btn-primary" @click="$wire.drawer = false" />
        </x-slot:actions>
    </x-drawer>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>


    <script>
        let map;
        let markers = [];
      
    
        document.addEventListener('livewire:initialized', function () {
            map = L.map('map').setView([-2.50, 132.1908], 6);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);
    
            Livewire.on('strandingsUpdated', strandings => {                  
                updateMap(strandings);      
            });
    
            displayMap(@json($strandings));
        });
    
        function displayMap(strandings) {
            console.log(strandings);
            // Add new markers
            strandings.forEach((stranding) => {
                var latitude = Number(stranding.latitude);
                var longitude = Number(stranding.longitude);
                
                    var customIcon = L.Icon.extend({
                        options: {
                            iconSize: [25, 25],
                            iconAnchor: [15, 15],
                            popupAnchor: [0, -15],
                        }
                    });
                    var iconPath = new customIcon({ iconUrl: 'storage/' + stranding.group.icon });
    
                    if (!isNaN(latitude) && !isNaN(longitude)) {
                        var marker = L.marker([latitude, longitude], { icon: iconPath }).addTo(map).bindPopup(
                            `<container class="p-4 max-w-sm mx-auto bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700 sm:max-w-sm">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:gray-400">Species: <i>${stranding.species.species}</i></h5>
                                <table class="table-fixed w-full">
                                    <tbody>
                                        <tr>
                                            <td class="font-bold text-gray-700 dark:text-gray-400">Tanggal:</td>
                                            <td class="text-gray-700 dark:text-gray-400">${stranding.information_date}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-gray-700 dark:text-gray-400">Provinsi:</td>
                                            <td class="text-gray-700 dark:text-gray-400">${stranding.province.province}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-gray-700 dark:text-gray-400">Lokasi:</td>
                                            <td class="text-gray-700 dark:text-gray-400">${stranding.location}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-gray-700 dark:text-gray-400">Latitude:</td>
                                            <td class="text-gray-700 dark:text-gray-400">${latitude}</td>
                                        </tr>
                                        <tr>
                                            <td class="font-bold text-gray-700 dark:text-gray-400">Longitude:</td>
                                            <td class="text-gray-700 dark:text-gray-400">${longitude}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </container>`
                        );
                        markers.push(marker);
                    } else {
                        console.error('Invalid coordinates', latitude, longitude); // Debugging output
                    }
                
            });

            
        }

        function updateMap(strandings){
            //remove all markers
            markers.forEach(marker => {
                map.removeLayer(marker);
            });
            markers = [];
            //add new markers
            strandings.forEach(stranding => {
                stranding.forEach(strandingdata => {
                    var latitude = Number(strandingdata.latitude);
                    var longitude = Number(strandingdata.longitude);
                            
                            
                    var customIcon = L.Icon.extend({
                        options: {
                            iconSize: [30, 30],
                            iconAnchor: [15, 15],
                            popupAnchor: [0, -15],
                        }
                    });

                    var iconPath = new customIcon({ iconUrl: 'storage/' + strandingdata.group.icon });

                            if (!isNaN(latitude) && !isNaN(longitude)) {
                                var marker = L.marker([latitude, longitude], { icon: iconPath }).addTo(map).bindPopup(
                                    `<container class="p-4 max-w-sm mx-auto bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700 sm:max-w-sm">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:gray-400">Species: <i>${strandingdata.species.species}</i></h5>
                                        <table class="table-fixed w-full">
                                            <tbody>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Tanggal:</td>
                                                    <td class="text-gray-700 dark:text-gray-400">${strandingdata.information_date}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Provinsi:</td>
                                                    <td class="text-gray-700 dark:text-gray-400">${strandingdata.province.province}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Lokasi:</td>
                                                    <td class="text-gray-700 dark:text-gray-400">${strandingdata.location}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Latitude:</td>
                                                    <td class="text-gray-700 dark:text-gray-400">${latitude}</td>
                                                </tr>
                                                <tr>
                                                    <td class="font-bold text-gray-700 dark:text-gray-400">Longitude:</td>
                                                    <td class="text-gray-700 dark:text-gray-400">${longitude}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </container>`
                                );
                                markers.push(marker);
                            } else {
                                console.error('Invalid coordinates', latitude, longitude); // Debugging output
                            }
                            
                        });
            });
        }

    

        
    </script>
</div>
