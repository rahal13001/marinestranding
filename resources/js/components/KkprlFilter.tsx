import React, { useState } from "react";
import { Button } from "@/components/ui/button";
import { Drawer, DrawerContent, DrawerTitle, DrawerDescription } from "@/components/ui/drawer";
import { Filter } from "lucide-react";
import { Label } from "@/components/ui/label";
import { ScrollArea } from "@/components/ui/scroll-area";
import Select from "react-select";
import { Switch } from "@/components/ui/switch"; // Import switch component
import { router } from "@inertiajs/react";

interface FilterData {
  province: { value: string; label: string }[];
  zone: { value: string; label: string }[];
  subject_status: { value: string; label: string }[];
  showKkprlMaps: boolean;
  showKkprlUses: boolean;
}

export default function KkprlFilter({
  provinces = [],
  zones = [],
  subjectStatuses = [],
  selectedFilters = { province: [], zone: [], subject_status: [], showKkprlMaps: true, showKkprlUses: true }
}: {
  provinces: { id: string; province: string }[];
  zones: { id: string; zone_name: string }[];
  subjectStatuses: { id: string; subject_status: string }[];
  selectedFilters: { province: string[]; zone: string[]; subject_status: string[]; showKkprlMaps: boolean; showKkprlUses: boolean };
}) {
  const [isOpen, setIsOpen] = useState(false);

  const provinceOptions = provinces.map((p) => ({ value: p.id.toString(), label: p.province }));
  const zoneOptions = zones.map((z) => ({ value: z.id.toString(), label: z.zone_name }));
  const subjectStatusOptions = subjectStatuses.map((s) => ({ value: s.id.toString(), label: s.subject_status }));

  const [values, setValues] = useState<FilterData>({
    province: provinceOptions.filter((p) => selectedFilters.province.includes(p.value)),
    zone: zoneOptions.filter((z) => selectedFilters.zone.includes(z.value)),
    subject_status: subjectStatusOptions.filter((s) => selectedFilters.subject_status.includes(s.value)),
    showKkprlMaps: selectedFilters.showKkprlMaps,
    showKkprlUses: selectedFilters.showKkprlUses,
  });

  const resetFilters = (e: React.MouseEvent<HTMLButtonElement>) => {
    e.preventDefault(); // ✅ Fix: Ensure event is properly received
  
    setValues({ province: [], zone: [], subject_status: [], showKkprlMaps: true, showKkprlUses: true });
  
    setTimeout(() => {
      router.post("/ruanglaut", { province: [], zone: [], subject_status: [], showKkprlMaps: true, showKkprlUses: true });
    }, 0);
  
  };

  const closeDrawer = () => {
    if (document.activeElement instanceof HTMLElement) {
      document.activeElement.blur(); // ✅ Ensure no focused element inside the drawer
    }
    setTimeout(() => setIsOpen(false), 50); // ✅ Delay closing slightly to prevent errors
  };

  const handleSubmit = (e: React.MouseEvent<HTMLButtonElement>) => {
    e.preventDefault(); // ✅ Fix: Ensure event is properly received
    
    router.post("/ruanglaut", {
      province: values.province.map((p) => p.value),
      zone: values.zone.map((z) => z.value),
      subject_status: values.subject_status.map((s) => s.value),
      showKkprlMaps: values.showKkprlMaps,
      showKkprlUses: values.showKkprlUses,
    },
  );
  
    // closeDrawer(); // ✅ Close the drawer after submitting
  };

  return (
    <div>
      {/* Filter Button */}
      <div className="absolute right-[45px] top-[158px] sm:top-[40px] md:top-[118px] md:right-13">
        <Button variant="outline" onClick={() => setIsOpen(true)} className="flex items-center gap-2 shadow-md px-3 py-2 text-sm md:text-base">
          <Filter className="w-4 h-4" /> Filters
        </Button>
      </div>

      {/* Sidebar Drawer */}
      <Drawer
          open={isOpen}
          onOpenChange={(open) => {
            if (!open) closeDrawer(); // ✅ Ensure focus is removed before closing
            else setIsOpen(true);
          }}
          direction="right"
        >
       <DrawerContent
          tabIndex={-1} // ✅ Prevents focus issues
          className="w-full md:w-80 max-w-md bg-white dark:bg-gray-900 shadow-lg transform transition-transform ease-in-out duration-300 border-l border-gray-300 dark:border-gray-700 flex flex-col"
        >
          <DrawerTitle className="p-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Filters</DrawerTitle>
          <DrawerDescription className="p-4 text-gray-600 dark:text-gray-300">Gunakan Filter Untuk Menyaring Data</DrawerDescription>

          <ScrollArea className="p-4 text-gray-600 dark:text-gray-300 flex-grow">
            <div className="space-y-4">
              {/* Toggle Show KKPRL Maps */}
              <div className="flex justify-between items-center">
                <Label>Tampilkan Peta Tata Ruang</Label>
                <Switch checked={values.showKkprlMaps} onCheckedChange={(checked) => setValues({ ...values, showKkprlMaps: checked })} />
              </div>

              {/* Toggle Show KKPRL Uses */}
              <div className="flex justify-between items-center">
                <Label>Tampilkan Peta KKPRL</Label>
                <Switch checked={values.showKkprlUses} onCheckedChange={(checked) => setValues({ ...values, showKkprlUses: checked })} />
              </div>

              {/* Province Filter */}
              <Label>Provinsi</Label>
              <Select
                isMulti
                options={provinceOptions}
                value={values.province}
                onChange={(selected) => setValues({ ...values, province: [...selected] })}
                classNamePrefix="react-select"
                placeholder="Pilih Provinsi..."
              />

              {/* Zone Filter (Only for KKPRL Maps) */}
              <Label>Zona (Peta Tata Ruang)</Label>
              <Select
                isMulti
                options={zoneOptions}
                value={values.zone}
                onChange={(selected) => setValues({ ...values, zone: [...selected] })}
                classNamePrefix="react-select"
                placeholder="Pilih Zona..."
              />

              {/* Subject Status Filter (Only for KKPRL Uses) */}
              <Label>Bentuk KKPRL (Peta KKPRL)</Label>
              <Select
                isMulti
                options={subjectStatusOptions}
                value={values.subject_status}
                onChange={(selected) => setValues({ ...values, subject_status: [...selected] })}
                classNamePrefix="react-select"
                placeholder="Pilih Bentuk KKPRL..."
              />
            </div>
          </ScrollArea>

          <div className="p-4 border-t dark:border-gray-700 bg-white dark:bg-gray-900 flex justify-between gap-2 w-full">
            <Button onClick={(e) => { handleSubmit(e); }} className="flex-1">
              Terapkan
            </Button>

            <Button variant="outline" onClick={(e) => resetFilters(e)} className="flex-1">
              Reset
            </Button>
          </div>
        </DrawerContent>
      </Drawer>
    </div>
  );
}
