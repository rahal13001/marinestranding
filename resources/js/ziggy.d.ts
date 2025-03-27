/* This file is generated by Ziggy. */
declare module 'ziggy-js' {
  interface RouteList {
    "debugbar.openhandler": [],
    "debugbar.clockwork": [
        {
            "name": "id",
            "required": true
        }
    ],
    "debugbar.assets.css": [],
    "debugbar.assets.js": [],
    "debugbar.cache.delete": [
        {
            "name": "key",
            "required": true
        },
        {
            "name": "tags",
            "required": false
        }
    ],
    "debugbar.queries.explain": [],
    "filament.exports.download": [
        {
            "name": "export",
            "required": true,
            "binding": "id"
        }
    ],
    "filament.imports.failed-rows.download": [
        {
            "name": "import",
            "required": true,
            "binding": "id"
        }
    ],
    "filament.biotalaut.auth.login": [],
    "filament.biotalaut.auth.logout": [],
    "filament.biotalaut.pages.dashboard": [],
    "filament.biotalaut.resources.stranding.categories.index": [],
    "filament.biotalaut.resources.stranding.categories.create": [],
    "filament.biotalaut.resources.stranding.categories.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.categories.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.codes.index": [],
    "filament.biotalaut.resources.stranding.codes.create": [],
    "filament.biotalaut.resources.stranding.codes.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.codes.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.families.index": [],
    "filament.biotalaut.resources.stranding.families.create": [],
    "filament.biotalaut.resources.stranding.families.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.families.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.generas.index": [],
    "filament.biotalaut.resources.stranding.generas.create": [],
    "filament.biotalaut.resources.stranding.generas.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.generas.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.groups.index": [],
    "filament.biotalaut.resources.stranding.groups.create": [],
    "filament.biotalaut.resources.stranding.groups.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.groups.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.methods.index": [],
    "filament.biotalaut.resources.stranding.methods.create": [],
    "filament.biotalaut.resources.stranding.methods.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.provinces.index": [],
    "filament.biotalaut.resources.stranding.provinces.create": [],
    "filament.biotalaut.resources.stranding.provinces.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.provinces.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.quantities.index": [],
    "filament.biotalaut.resources.stranding.quantities.create": [],
    "filament.biotalaut.resources.stranding.quantities.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.quantities.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.species.index": [],
    "filament.biotalaut.resources.stranding.species.create": [],
    "filament.biotalaut.resources.stranding.species.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.species.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.laporan-biota-laut-terdampar.index": [],
    "filament.biotalaut.resources.laporan-biota-laut-terdampar.create": [],
    "filament.biotalaut.resources.laporan-biota-laut-terdampar.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.laporan-biota-laut-terdampar.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.users.index": [],
    "filament.biotalaut.resources.stranding.users.create": [],
    "filament.biotalaut.resources.stranding.users.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.stranding.users.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.shield.roles.index": [],
    "filament.biotalaut.resources.shield.roles.create": [],
    "filament.biotalaut.resources.shield.roles.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.biotalaut.resources.shield.roles.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.auth.login": [],
    "filament.kkprl.auth.logout": [],
    "filament.kkprl.pages.dashboard": [],
    "filament.kkprl.resources.activities.index": [],
    "filament.kkprl.resources.activities.create": [],
    "filament.kkprl.resources.activities.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.activities.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.activitystatuses.index": [],
    "filament.kkprl.resources.activitystatuses.create": [],
    "filament.kkprl.resources.activitystatuses.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.activitystatuses.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.kkprlmaps.index": [],
    "filament.kkprl.resources.kkprlmaps.create": [],
    "filament.kkprl.resources.kkprlmaps.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.kkprlmaps.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.kkprluses.index": [],
    "filament.kkprl.resources.kkprluses.create": [],
    "filament.kkprl.resources.kkprluses.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.kkprluses.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.provinces.index": [],
    "filament.kkprl.resources.provinces.create": [],
    "filament.kkprl.resources.provinces.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.provinces.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.regulations.index": [],
    "filament.kkprl.resources.regulations.create": [],
    "filament.kkprl.resources.regulations.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.regulations.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.zones.index": [],
    "filament.kkprl.resources.zones.create": [],
    "filament.kkprl.resources.zones.view": [
        {
            "name": "record",
            "required": true
        }
    ],
    "filament.kkprl.resources.zones.edit": [
        {
            "name": "record",
            "required": true
        }
    ],
    "sanctum.csrf-cookie": [],
    "livewire.update": [],
    "livewire.upload-file": [],
    "livewire.preview-file": [
        {
            "name": "filename",
            "required": true
        }
    ],
    "login": [],
    "rzwp3k": [],
    "kkprl": [],
    "logout": [],
    "mamalia": [],
    "scramble.docs.ui": [],
    "scramble.docs.document": []
}
}
export {};
