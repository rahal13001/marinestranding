import { useState, useEffect } from "react";
import { cn } from "@/lib/utils";
import { Menu, LayoutDashboard, Map, Sun, Moon, X, Fish } from "lucide-react";
import { Button } from "@/components/ui/button";
import { Card } from "@/components/ui/card";
import { Link } from '@inertiajs/react'
import WhaleIcon from "@/components/icons/WhaleIcon";


interface LayoutProps {
  children: React.ReactNode;
}

const Layout: React.FC<LayoutProps> = ({ children }) => {
  const [isSidebarOpen, setIsSidebarOpen] = useState<boolean>(false);
  const [isMobileSidebarOpen, setIsMobileSidebarOpen] = useState<boolean>(false);
  const [activeMenu, setActiveMenu] = useState<string>("Biota Laut Terdampar");
  const [theme, setTheme] = useState<string>(() => {
    if (typeof window !== "undefined") {
      return localStorage.getItem("theme") || (window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light");
    }
    return "light";
  });

 useEffect(() => {
    document.documentElement.classList.toggle("dark", theme === "dark");
    localStorage.setItem("theme", theme);
  }, [theme]);

  // Close mobile sidebar when clicking outside
  useEffect(() => {
    const handleOutsideClick = (event: MouseEvent) => {
      const sidebar = document.getElementById("mobile-sidebar");
      const menuButton = document.getElementById("menu-button");

      if (
        isMobileSidebarOpen &&
        sidebar &&
        !sidebar.contains(event.target as Node) &&
        menuButton !== event.target
      ) {
        setIsMobileSidebarOpen(false);
      }
    };

    document.addEventListener("mousedown", handleOutsideClick);
    return () => document.removeEventListener("mousedown", handleOutsideClick);
  }, [isMobileSidebarOpen]);

  return (
    <div className="flex flex-col min-h-screen">
      {/* Top Bar */}
      <div className="fixed top-0 left-0 w-full h-16 bg-gray-100 dark:bg-gray-900 flex items-center justify-between px-6 shadow-md z-50">
        {/* Left Side - Logo & Hamburger Button */}
        <div className="flex items-center gap-2">
          <button 
            id="menu-button"
            onClick={() => setIsMobileSidebarOpen(!isMobileSidebarOpen)} 
            className="lg:hidden p-2 rounded-md hover:bg-gray-200 dark:hover:bg-gray-800 transition"
          >
            <Menu className="w-6 h-6 text-gray-900 dark:text-white" />
          </button>
          <img src="/img/logoweb.png" alt="LPSPL Sorong" className="w-10 h-10" />
          <h2 className="text-base md:text-lg sm:text-sm font-bold text-gray-900 dark:text-white">LPSPL Sorong</h2>
        </div>

        {/* Right Side - Theme Toggle */}
        <Button
          variant="ghost"
          className="flex items-center gap-2 p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 transition"
          onClick={() => setTheme(theme === "dark" ? "light" : "dark")}
        >
          {theme === "dark" ? <Sun className="w-6 h-6 text-white" /> : <Moon className="w-6 h-6 text-gray-900" />}
          <span className="text-gray-900 dark:text-white">{theme === "dark" ? "Light Mode" : "Dark Mode"}</span>
        </Button>
      </div>

      {/* Layout Wrapper */}
      <div className="flex pt-16">
        {/* Desktop Sidebar - Collapsible */}
        <div
          className={cn(
            "hidden lg:flex flex-col fixed top-16 left-0 h-full bg-gray-100 dark:bg-gray-900 shadow-lg p-4 transition-all duration-300 ease-in-out",
            isSidebarOpen ? "w-64" : "w-16"
          )}
        >
          {/* Sidebar Toggle Button */}
          <Button
            variant="ghost"
            className="mb-4 flex items-center gap-2 hover:bg-gray-200 dark:hover:bg-gray-800 p-2 rounded-lg"
            onClick={() => setIsSidebarOpen(!isSidebarOpen)}
          >
            <Menu className="w-6 h-6 text-gray-900 dark:text-white" />
            {isSidebarOpen && <span className="font-semibold text-gray-900 dark:text-white">Web GIS LPSPL Sorong</span>}
          </Button>

          {/* Sidebar Menu */}
          <nav className="space-y-2">
            {[
              { name: "Biota Laut Terdampar", icon: WhaleIcon, link:'/' },
              { name: "Tata Ruang Laut", icon: Map, link:'ruanglaut' },
            ].map((item) => (
              <Link key={item.name} href={item.link}>  {/* ✅ Fix: Move key here */}
                <Button
                  variant="ghost"
                  className={cn(
                    "flex w-full items-center gap-2 justify-start p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 transition",
                    activeMenu === item.name ? "bg-blue-300 dark:bg-blue-600 font-semibold" : ""
                  )}
                  onClick={() => setActiveMenu(item.name)}
                >
                  <item.icon className="w-6 h-6 text-gray-900 dark:text-white" />
                  {isSidebarOpen && <span className="text-gray-900 dark:text-white">{item.name}</span>}
                </Button>
              </Link>
            ))}
          </nav>
        </div>

        {/* Mobile Sidebar - Fullscreen when open */}
        <div
          id="mobile-sidebar"
          className={cn(
            "fixed top-0 left-0 h-full w-64 bg-gray-100 dark:bg-gray-900 shadow-lg p-4 transition-transform duration-300 ease-in-out z-50 lg:hidden",
            isMobileSidebarOpen ? "translate-x-0" : "-translate-x-full"
          )}
        >
          {/* Close Button for Mobile */}
          <Button
            variant="ghost"
            className="mb-4 flex items-center gap-2 hover:bg-gray-200 dark:hover:bg-gray-800 p-2 rounded-lg"
            onClick={() => setIsMobileSidebarOpen(false)}
          >
            <X className="w-6 h-6 text-gray-900 dark:text-white" />
            <span className="font-semibold text-gray-900 dark:text-white">Close Menu</span>
          </Button>

          {/* Menu Items */}
          <nav className="space-y-2">
            {[
              { name: "Biota Laut Terdampar", icon: WhaleIcon, link:'/' },
              { name: "Tata Ruang Laut", icon: Map, link:'ruanglaut' },
            ].map((item) => (
              <Link key={item.name} href={item.link}>  {/* ✅ Fix: Move key here */}
                <Button
                  variant="ghost"
                  className={cn("flex w-full items-center gap-2 justify-start p-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-800 transition",
                    activeMenu === item.name ? "bg-blue-300 dark:bg-blue-600 font-semibold" : "")}
                  onClick={() => {
                    setActiveMenu(item.name);
                    setIsMobileSidebarOpen(false);
                  }}
                >
                  <item.icon className="w-6 h-6 text-gray-900 dark:text-white" />
                  <span className="text-gray-900 dark:text-white">{item.name}</span>
                </Button>
              </Link>
            ))}
          </nav>
        </div>

        {/* Main Content */}
        <div className={cn("flex-1 min-h-screen p-6 transition-all bg-gray-100 dark:bg-gray-900", isSidebarOpen ? "lg:ml-64" : "lg:ml-16")}> 
          <Card className="p-6 shadow-md bg-gray-100 dark:bg-gray-900 dark:text-white"> 
            <h1 className="text-2xl font-bold text-gray-900 dark:text-white">Web GIS {activeMenu}</h1>
            {children}
          </Card>
        </div>
      </div>
    </div>
  );
};

export default Layout;
