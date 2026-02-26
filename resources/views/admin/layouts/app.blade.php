<!doctype html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">
    <head>
        <meta http-equiv="content-type" content="text/html;charset=utf-8" />
        <title>Metronic - Tailwind CSS Light Sidebar</title>
        <meta charset="utf-8" />
        <meta content="follow, index" name="robots" />
        <link href="index.html" rel="canonical" />
        <meta content="width=device-width,initial-scale=1,shrink-to-fit=no" name="viewport" />
        <meta
            content="Tailwind CSS based HTML and JavaScript toolkit for building modern and scalable web applications"
            name="description"
        />
        <meta content="@keenthemes" name="twitter:site" />
        <meta content="@keenthemes" name="twitter:creator" />
        <meta content="summary_large_image" name="twitter:card" />
        <meta content="Metronic - Tailwind CSS Light Sidebar" name="twitter:title" />
        <meta
            content="Tailwind CSS based HTML and JavaScript toolkit for building modern and scalable web applications"
            name="twitter:description"
        />
        <meta content="{{ asset('/public/hotel-management-admin/') }}/media/app/og-image.png" name="twitter:image" />
        <meta content="en_US" property="og:locale" />
        <meta content="website" property="og:type" />
        <meta content="Metronic - Tailwind CSS Light Sidebar" property="og:title" />
        <link
            href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap"
            rel="stylesheet"
        />
        <link href="{{ asset('/public/hotel-management-admin/') }}/vendors/apexcharts/apexcharts.css" rel="stylesheet" />
        <link href="{{ asset('/public/hotel-management-admin/') }}/vendors/keenicons/styles.bundle.css" rel="stylesheet" />
        <link href="{{ asset('/public/hotel-management-admin/') }}/css/styles.css" rel="stylesheet" />

        <style>
            /* Fixed footer for admin layout (sidebar-aware) */
            :root {
                --kt-footer-height: 0px;
            }

            .kt-footer.kt-footer-fixed {
                position: fixed;
                bottom: 0;
                inset-inline-end: 0;
                inset-inline-start: 0;
                z-index: 15;
                background: var(--background);
                border-top: 1px solid var(--border);
            }

            #headerContainer{
                border-bottom: 1px solid var(--border);
            }
            /* Ensure content never hides under the fixed footer */
            main#content {
                   overflow-y: auto;
                    padding: 20px 20px 95px 20px;
            }

            @media (min-width: 64rem) {
                body.demo1.kt-sidebar-fixed .kt-footer.kt-footer-fixed {
                    inset-inline-start: var(--sidebar-width);
                }
            }

            /* requare */
            /* In your CSS file (e.g., app.css) */
            .required-label:after {
            content: " *";
            color: red; /* Tailwind color classes will not work here */
            }

        </style>
    </head>
    <body
        class="antialiased flex h-full overflow-hidden text-base text-foreground bg-background demo1 kt-sidebar-fixed kt-header-fixed"
    >
        <script>
            const defaultThemeMode = "light"; // light|dark|system
            let themeMode;

            if (document.documentElement) {
                if (localStorage.getItem("kt-theme")) {
                    themeMode = localStorage.getItem("kt-theme");
                } else if (document.documentElement.hasAttribute("data-kt-theme-mode")) {
                    themeMode = document.documentElement.getAttribute("data-kt-theme-mode");
                } else {
                    themeMode = defaultThemeMode;
                }

                if (themeMode === "system") {
                    themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
                }

                document.documentElement.classList.add(themeMode);
            }
        </script>
        <div class="flex grow min-h-0">
            <div
                class="kt-sidebar bg-background border-e border-e-border fixed top-0 bottom-0 z-20 hidden lg:flex flex-col items-stretch shrink-0 [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]"
                data-kt-drawer="true"
                data-kt-drawer-class="kt-drawer kt-drawer-start top-0 bottom-0"
                id="sidebar"
            >
                <div
                    class="kt-sidebar-header hidden lg:flex items-center relative justify-between px-3 lg:px-6 shrink-0"
                    id="sidebar_header"
                >
                    <a class="dark:hidden" href="index.html"
                        ><img
                            class="default-logo min-h-[22px] max-w-none"
                            src="{{ asset('/public/hotel-management-admin/') }}/media/app/apple-touch-icon.png" />
                        <img
                            class="small-logo min-h-[22px] max-w-none"
                            src="{{ asset('/public/hotel-management-admin/') }}/media/app/mini-logo.svg" /></a
                    ><a class="hidden dark:block" href="index.html"
                        ><img
                            class="default-logo min-h-[22px] max-w-none"
                            src="{{ asset('/public/hotel-management-admin/') }}/media/app/default-logo-dark.svg" />
                        <img
                            class="small-logo min-h-[22px] max-w-none"
                            src="{{ asset('/public/hotel-management-admin/') }}/media/app/mini-logo.svg" /></a
                    ><button
                        class="kt-btn kt-btn-outline kt-btn-icon size-[30px] absolute start-full top-2/4 -translate-x-2/4 -translate-y-2/4 rtl:translate-x-2/4"
                        data-kt-toggle="body"
                        data-kt-toggle-class="kt-sidebar-collapse"
                        id="sidebar_toggle"
                    >
                        <i
                            class="ki-filled ki-black-left-line kt-toggle-active:rotate-180 transition-all duration-300 rtl:translate rtl:rotate-180 rtl:kt-toggle-active:rotate-0"
                        ></i>
                    </button>
                </div>
                <div class="kt-sidebar-content flex grow shrink-0 py-5 pe-2" id="sidebar_content">
                    <div
                        class="kt-scrollable-y-hover grow shrink-0 flex ps-2 lg:ps-5 pe-1 lg:pe-3"
                        data-kt-scrollable="true"
                        data-kt-scrollable-dependencies="#sidebar_header"
                        data-kt-scrollable-height="auto"
                        data-kt-scrollable-offset="0px"
                        data-kt-scrollable-wrappers="#sidebar_content"
                        id="sidebar_scrollable"
                    >
                        <div
                            class="kt-menu flex flex-col grow gap-1"
                            data-kt-menu="true"
                            data-kt-menu-accordion-expand-all="false"
                            id="sidebar_menu"
                        >
                            <div
                                class="kt-menu-item here show"
                                data-kt-menu-item-toggle="accordion"
                                data-kt-menu-item-trigger="click"
                            >
                                <div
                                    class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]"
                                    tabindex="0"
                                >
                                    <span class="kt-menu-icon items-start text-muted-foreground w-[20px]"
                                        ><i class="ki-filled ki-element-11 text-lg"></i></span
                                    ><span
                                        class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary"
                                        >Dashboards</span
                                    ><span
                                        class="kt-menu-arrow text-muted-foreground w-[20px] shrink-0 justify-end ms-1 me-[-10px]"
                                        ><span class="inline-flex kt-menu-item-show:hidden"
                                            ><i class="ki-filled ki-plus text-[11px]"></i></span
                                        ><span class="hidden kt-menu-item-show:inline-flex"
                                            ><i class="ki-filled ki-minus text-[11px]"></i></span
                                    ></span>
                                </div>
                                <div
                                    class="kt-menu-accordion gap-1 ps-[10px] relative before:absolute before:start-[20px] before:top-0 before:bottom-0 before:border-s before:border-border"
                                >
                                    <div class="kt-menu-item active">
                                        <a
                                            class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]"
                                            href="{{ route('admin.index') }}"
                                            tabindex="0"
                                            ><span
                                                class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full rtl:before:translate-x-1/2 before:-translate-y-1/2 kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"
                                            ></span
                                            ><span
                                                class="kt-menu-title text-2sm font-normal text-foreground kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary"
                                                >Light Sidebar</span
                                            ></a
                                        >
                                    </div>
                                    <div class="kt-menu-item">
                                        <a
                                            class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 dark:menu-item-active:border-border kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]"
                                            href="dashboards/dark-sidebar.html"
                                            tabindex="0"
                                            ><span
                                                class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full rtl:before:translate-x-1/2 before:-translate-y-1/2 kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"
                                            ></span
                                            ><span
                                                class="kt-menu-title text-2sm font-normal text-foreground kt-menu-item-active:text-primary kt-menu-item-active:font-semibold kt-menu-link-hover:!text-primary"
                                                >Dark Sidebar</span
                                            ></a
                                        >
                                    </div>
                                </div>
                            </div>
                            <div
                                class="kt-menu-item {{ request()->routeIs('admin.reservations.*') ? 'here show' : '' }}"
                                data-kt-menu-item-toggle="accordion"
                                data-kt-menu-item-trigger="click"
                            >
                                <div
                                    class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]"
                                    tabindex="0"
                                >
                                    <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                        <!-- Inline front-desk SVG to ensure consistent rendering -->
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline-block">
                                            <rect x="2" y="4" width="20" height="6" rx="1" stroke="currentColor" stroke-width="1.2" fill="none" />
                                            <path d="M4 11v6a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-6" stroke="currentColor" stroke-width="1.2" fill="none" />
                                            <circle cx="8" cy="8" r="1" fill="currentColor" />
                                            <circle cx="16" cy="8" r="1" fill="currentColor" />
                                        </svg>
                                    </span>
                                    <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">Front Desk</span>
                                    <span class="kt-menu-arrow text-muted-foreground w-[20px] shrink-0 justify-end ms-1 me-[-10px]"><span class="inline-flex kt-menu-item-show:hidden"><i class="ki-filled ki-plus text-[11px]"></i></span><span class="hidden kt-menu-item-show:inline-flex"><i class="ki-filled ki-minus text-[11px]"></i></span></span>
                                </div>
                                    <div class="kt-menu-accordion gap-1 ps-[10px] relative before:absolute before:start-[20px] before:top-0 before:bottom-0 before:border-s before:border-border">
                                    <div class="kt-menu-item {{ request()->routeIs('admin.reservations.index') || request()->routeIs('admin.reservations.show') || request()->routeIs('admin.reservations.checkin') || request()->routeIs('admin.reservations.checkout') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.reservations.index') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Reservations</span>
                                        </a>
                                    </div>
                                    <div class="kt-menu-item {{ request()->routeIs('admin.reservations.calendar') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.reservations.calendar') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Booking Calendar</span>
                                        </a>
                                    </div>
                                    <div class="kt-menu-item {{ request()->routeIs('admin.reservations.walkin') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.reservations.walkin') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Walk-in Booking</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="kt-menu-item {{ request()->routeIs('admin.rooms.*') ? 'here show' : '' }}"
                                data-kt-menu-item-toggle="accordion"
                                data-kt-menu-item-trigger="click"
                            >
                                <div
                                    class="kt-menu-link flex items-center grow cursor-pointer border border-transparent gap-[10px] ps-[10px] pe-[10px] py-[6px]"
                                    tabindex="0"
                                >
                                    <span class="kt-menu-icon items-start text-muted-foreground w-[20px]">
                                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="inline-block">
                                            <rect x="3" y="7" width="18" height="10" rx="1" stroke="currentColor" stroke-width="1.2" fill="none" />
                                            <path d="M7 7V5a1 1 0 0 1 1-1h8a1 1 0 0 1 1 1v2" stroke="currentColor" stroke-width="1.2" fill="none" />
                                            <path d="M8 12h.01M12 12h.01M16 12h.01" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" />
                                        </svg>
                                    </span>
                                    <span class="kt-menu-title text-sm font-medium text-foreground kt-menu-item-active:text-primary kt-menu-link-hover:!text-primary">Room Management</span>
                                    <span class="kt-menu-arrow text-muted-foreground w-[20px] shrink-0 justify-end ms-1 me-[-10px]"><span class="inline-flex kt-menu-item-show:hidden"><i class="ki-filled ki-plus text-[11px]"></i></span><span class="hidden kt-menu-item-show:inline-flex"><i class="ki-filled ki-minus text-[11px]"></i></span></span>
                                </div>
                                <div class="kt-menu-accordion gap-1 ps-[10px] relative before:absolute before:start-[20px] before:top-0 before:bottom-0 before:border-s before:border-border">
                                    <div class="kt-menu-item {{ request()->routeIs('admin.rooms.index') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.rooms.index') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Rooms</span>
                                        </a>
                                    </div>
                                    <div class="kt-menu-item {{ request()->routeIs('admin.rooms.types.*') || request()->routeIs('admin.rooms.types.index') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.rooms.types.index') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Room Types</span>
                                        </a>
                                    </div>
                                    <div class="kt-menu-item {{ request()->routeIs('admin.rooms.floors.*') || request()->routeIs('admin.rooms.floors.index') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.rooms.floors.index') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Floors</span>
                                        </a>
                                    </div>
                                    <div class="kt-menu-item {{ request()->routeIs('admin.rooms.amenities.*') || request()->routeIs('admin.rooms.amenities.index') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.rooms.amenities.index') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Amenities</span>
                                        </a>
                                    </div>
                                    <div class="kt-menu-item {{ request()->routeIs('admin.rooms.services.*') || request()->routeIs('admin.rooms.services.index') ? 'active' : '' }}">
                                        <a class="kt-menu-link border border-transparent items-center grow kt-menu-item-active:bg-accent/60 kt-menu-item-active:rounded-lg hover:bg-accent/60 hover:rounded-lg gap-[14px] ps-[10px] pe-[10px] py-[8px]" href="{{ route('admin.rooms.services.index') }}" tabindex="0">
                                            <span class="kt-menu-bullet flex w-[6px] -start-[3px] rtl:start-0 relative before:absolute before:top-0 before:size-[6px] before:rounded-full kt-menu-item-active:before:bg-primary kt-menu-item-hover:before:bg-primary"></span>
                                            <span class="kt-menu-title text-2sm font-normal text-foreground">Extra Services</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                       
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-wrapper flex grow flex-col min-h-0">
                <header
                    class="kt-header fixed top-0 z-10 start-0 end-0 flex items-stretch shrink-0 bg-background"
                    data-kt-sticky="true"
                    data-kt-sticky-class="border-b border-border"
                    data-kt-sticky-name="header"
                    id="header"
                >
                    <div class="kt-container-fixed flex justify-between items-stretch lg:gap-4" id="headerContainer">
                        <div class="flex gap-2.5 lg:hidden items-center -ms-1">
                            <a class="shrink-0" href="index.html"
                                ><img
                                    class="max-h-[25px] w-full"
                                    src="{{ asset('/public/hotel-management-admin/') }}/media/app/mini-logo.svg"
                            /></a>
                            <div class="flex items-center">
                                <button class="kt-btn kt-btn-icon kt-btn-ghost" data-kt-drawer-toggle="#sidebar">
                                    <i class="ki-filled ki-menu"></i></button
                                ><button
                                    class="kt-btn kt-btn-icon kt-btn-ghost"
                                    data-kt-drawer-toggle="#mega_menu_wrapper"
                                >
                                    <i class="ki-filled ki-burger-menu-2"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex items-stretch" id="megaMenuContainer">
                            <div
                                class="flex items-stretch [--kt-reparent-mode:prepend] [--kt-reparent-target:body] lg:[--kt-reparent-target:#megaMenuContainer] lg:[--kt-reparent-mode:prepend]"
                                data-kt-reparent="true"
                            >
                                <div
                                    class="hidden lg:flex lg:items-stretch [--kt-drawer-enable:true] lg:[--kt-drawer-enable:false]"
                                    data-kt-drawer="true"
                                    data-kt-drawer-class="kt-drawer kt-drawer-start fixed z-10 top-0 bottom-0 w-full me-5 max-w-[250px] p-5 lg:p-0 overflow-auto"
                                    id="mega_menu_wrapper"
                                >
                                    <div
                                        class="kt-menu flex-col lg:flex-row gap-5 lg:gap-7.5"
                                        data-kt-menu="true"
                                        id="mega_menu"
                                    >
                                        
</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center gap-2.5">
                            <button
                                class="group kt-btn kt-btn-ghost kt-btn-icon size-9 rounded-full hover:bg-primary/10 hover:[&amp;_i]:text-primary"
                                data-kt-modal-toggle="#search_modal"
                            >
                                <i class="ki-filled ki-magnifier text-lg group-hover:text-primary"></i></button
                            ><button
                                class="kt-btn kt-btn-ghost kt-btn-icon size-9 rounded-full hover:bg-primary/10 hover:[&amp;_i]:text-primary"
                                data-kt-drawer-toggle="#notifications_drawer"
                            >
                                <i class="ki-filled ki-notification-status text-lg"></i>
                            </button>
                            <div
                                class="hidden kt-drawer kt-drawer-end card flex-col max-w-[90%] w-[450px] top-5 bottom-5 end-5 rounded-xl border border-border"
                                data-kt-drawer="true"
                                data-kt-drawer-container="body"
                                id="notifications_drawer"
                            >
                                <div
                                    class="flex items-center justify-between gap-2.5 text-sm text-mono font-semibold px-5 py-2.5 border-b border-b-border"
                                    id="notifications_header"
                                >
                                    Notifications<button
                                        class="kt-btn kt-btn-sm kt-btn-icon kt-btn-dim shrink-0"
                                        data-kt-drawer-dismiss="true"
                                    >
                                        <i class="ki-filled ki-cross"></i>
                                    </button>
                                </div>
                                <div
                                    class="kt-tabs kt-tabs-line justify-between px-5 mb-2"
                                    data-kt-tabs="true"
                                    id="notifications_tabs"
                                >
                                    <div class="flex items-center gap-5">
                                        <button
                                            class="kt-tab-toggle py-3 active"
                                            data-kt-tab-toggle="#notifications_tab_all"
                                        >
                                            All</button
                                        ><button
                                            class="kt-tab-toggle py-3 relative"
                                            data-kt-tab-toggle="#notifications_tab_inbox"
                                        >
                                            Inbox<span
                                                class="rounded-full bg-green-500 size-[5px] absolute top-2 rtl:start-0 end-0 transform translate-y-1/2 translate-x-full"
                                            ></span></button
                                        ><button
                                            class="kt-tab-toggle py-3"
                                            data-kt-tab-toggle="#notifications_tab_team"
                                        >
                                            Team</button
                                        ><button
                                            class="kt-tab-toggle py-3"
                                            data-kt-tab-toggle="#notifications_tab_following"
                                        >
                                            Following
                                        </button>
                                    </div>
                                    <div class="kt-menu" data-kt-menu="true">
                                        <div
                                            class="kt-menu-item"
                                            data-kt-menu-item-offset="0,10px"
                                            data-kt-menu-item-placement="bottom-end"
                                            data-kt-menu-item-placement-rtl="bottom-start"
                                            data-kt-menu-item-toggle="dropdown"
                                            data-kt-menu-item-trigger="click|lg:hover"
                                        >
                                            <button class="kt-menu-toggle kt-btn kt-btn-icon kt-btn-ghost">
                                                <i class="ki-filled ki-setting-2"></i>
                                            </button>
                                            <div
                                                class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]"
                                                data-kt-menu-dismiss="true"
                                            >
                                                <div class="kt-menu-item">
                                                    <a class="kt-menu-link" href="#"
                                                        ><span class="kt-menu-icon"
                                                            ><i class="ki-filled ki-document"></i></span
                                                        ><span class="kt-menu-title">View</span></a
                                                    >
                                                </div>
                                                <div
                                                    class="kt-menu-item"
                                                    data-kt-menu-item-offset="-15px, 0"
                                                    data-kt-menu-item-placement="right-start"
                                                    data-kt-menu-item-toggle="dropdown"
                                                    data-kt-menu-item-trigger="click|lg:hover"
                                                >
                                                    <div class="kt-menu-link">
                                                        <span class="kt-menu-icon"
                                                            ><i class="ki-filled ki-notification-status"></i></span
                                                        ><span class="kt-menu-title">Export</span
                                                        ><span class="kt-menu-arrow"
                                                            ><i
                                                                class="ki-filled ki-right text-xs rtl:transform rtl:rotate-180"
                                                            ></i
                                                        ></span>
                                                    </div>
                                                    <div class="kt-menu-dropdown kt-menu-default w-full max-w-[175px]">
                                                        <div class="kt-menu-item">
                                                            <a
                                                                class="kt-menu-link"
                                                                href="account/home/settings-sidebar.html"
                                                                ><span class="kt-menu-icon"
                                                                    ><i class="ki-filled ki-sms"></i></span
                                                                ><span class="kt-menu-title">Email</span></a
                                                            >
                                                        </div>
                                                        <div class="kt-menu-item">
                                                            <a
                                                                class="kt-menu-link"
                                                                href="account/home/settings-sidebar.html"
                                                                ><span class="kt-menu-icon"
                                                                    ><i class="ki-filled ki-message-notify"></i></span
                                                                ><span class="kt-menu-title">SMS</span></a
                                                            >
                                                        </div>
                                                        <div class="kt-menu-item">
                                                            <a
                                                                class="kt-menu-link"
                                                                href="account/home/settings-sidebar.html"
                                                                ><span class="kt-menu-icon"
                                                                    ><i
                                                                        class="ki-filled ki-notification-status"
                                                                    ></i></span
                                                                ><span class="kt-menu-title">Push</span></a
                                                            >
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="kt-menu-item">
                                                    <a class="kt-menu-link" href="#"
                                                        ><span class="kt-menu-icon"
                                                            ><i class="ki-filled ki-pencil"></i></span
                                                        ><span class="kt-menu-title">Edit</span></a
                                                    >
                                                </div>
                                                <div class="kt-menu-item">
                                                    <a class="kt-menu-link" href="#"
                                                        ><span class="kt-menu-icon"
                                                            ><i class="ki-filled ki-trash"></i></span
                                                        ><span class="kt-menu-title">Delete</span></a
                                                    >
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button
                                class="kt-btn kt-btn-ghost kt-btn-icon size-9 rounded-full hover:bg-primary/10 hover:[&amp;_i]:text-primary"
                                data-kt-drawer-toggle="#chat_drawer"
                            >
                                <i class="ki-filled ki-messages text-lg"></i>
                            </button>
                            <div
                                class="hidden kt-drawer kt-drawer-end card flex-col max-w-[90%] w-[450px] top-5 bottom-5 end-5 rounded-xl border border-border"
                                data-kt-drawer="true"
                                data-kt-drawer-container="body"
                                id="chat_drawer"
                            >
                                <div>
                                    <div
                                        class="flex items-center justify-between gap-2.5 text-sm text-mono font-semibold px-5 py-3.5"
                                    >
                                        Chat<button
                                            class="kt-btn kt-btn-sm kt-btn-icon kt-btn-dim shrink-0"
                                            data-kt-drawer-dismiss="true"
                                        >
                                            <i class="ki-filled ki-cross"></i>
                                        </button>
                                    </div>
                                    <div class="border-b border-b-border"></div>
                                    <div class="border-b border-border py-2.5">
                                       
                                    </div>
                                </div>
                              
                            </div>
                            <div
                                data-kt-dropdown="true"
                                data-kt-dropdown-offset="10px, 10px"
                                data-kt-dropdown-offset-rtl="-10px, 10px"
                                data-kt-dropdown-placement="bottom-end"
                                data-kt-dropdown-placement-rtl="bottom-start"
                            >
                                <button
                                    class="kt-btn kt-btn-ghost kt-btn-icon size-9 rounded-full hover:bg-primary/10 hover:[&amp;_i]:text-primary kt-dropdown-open:bg-primary/10 kt-dropdown-open:[&amp;_i]:text-primary"
                                    data-kt-dropdown-toggle="true"
                                >
                                    <i class="ki-filled ki-element-11 text-lg"></i>
                                </button>
                                <div class="kt-dropdown-menu p-0 w-screen max-w-[320px]" data-kt-dropdown-menu="true">
                                    <div
                                        class="flex items-center justify-between gap-2.5 text-xs text-secondary-foreground font-medium px-5 py-3 border-b border-b-border"
                                    >
                                        <span>Apps</span><span>Enabled</span>
                                    </div>
                                    <div class="grid p-5 border-t border-t-border">
                                        <a class="kt-btn kt-btn-outline justify-center" href="account/integrations.html"
                                            >Go to Apps</a
                                        >
                                    </div>
                                </div>
                            </div>
                            <div
                                class="shrink-0"
                                data-kt-dropdown="true"
                                data-kt-dropdown-offset="10px, 10px"
                                data-kt-dropdown-offset-rtl="-20px, 10px"
                                data-kt-dropdown-placement="bottom-end"
                                data-kt-dropdown-placement-rtl="bottom-start"
                                data-kt-dropdown-trigger="click"
                            >
                                <div class="cursor-pointer shrink-0" data-kt-dropdown-toggle="true">
                                    <img
                                        alt=""
                                        class="size-9 rounded-full border-2 border-green-500 shrink-0"
                                        src="#"
                                    />
                                </div>
                                <div class="kt-dropdown-menu w-[250px]" data-kt-dropdown-menu="true">
                                    <div class="flex items-center justify-between px-2.5 py-1.5 gap-1.5">
                                        <div class="flex items-center gap-2">
                                            <img
                                                alt=""
                                                class="size-9 shrink-0 rounded-full border-2 border-green-500"
                                                src="#"
                                            />
                                            <div class="flex flex-col gap-1.5">
                                                <span class="text-sm text-foreground font-semibold leading-none"
                                                    >Cody Fisher</span
                                                ><a
                                                    class="text-xs text-secondary-foreground hover:text-primary font-medium leading-none"
                                                    href="account/home/get-started.html"
                                                    >c.fisher@gmail.com</a
                                                >
                                            </div>
                                        </div>
                                        <span class="kt-badge kt-badge-sm kt-badge-primary kt-badge-outline">Pro</span>
                                    </div>
                                    <ul class="kt-dropdown-menu-sub">
                                        <li><div class="kt-dropdown-menu-separator"></div></li>
                                        <li>
                                            <a class="kt-dropdown-menu-link" href="public-profile/profiles/default.html"
                                                ><i class="ki-filled ki-badge"></i>Public Profile</a
                                            >
                                        </li>
                                        <li>
                                            <a class="kt-dropdown-menu-link" href="account/home/user-profile.html"
                                                ><i class="ki-filled ki-profile-circle"></i>My Profile</a
                                            >
                                        </li>
                                        <li
                                            data-kt-dropdown="true"
                                            data-kt-dropdown-placement="right-start"
                                            data-kt-dropdown-trigger="hover"
                                        >
                                            <button class="kt-dropdown-menu-toggle" data-kt-dropdown-toggle="true">
                                                <i class="ki-filled ki-setting-2"></i>My Account<span
                                                    class="kt-dropdown-menu-indicator"
                                                    ><i class="ki-filled ki-right text-xs"></i
                                                ></span>
                                            </button>
                                            <div class="kt-dropdown-menu w-[220px]" data-kt-dropdown-menu="true">
                                                <ul class="kt-dropdown-menu-sub">
                                                    <li>
                                                        <a
                                                            class="kt-dropdown-menu-link"
                                                            href="account/home/get-started.html"
                                                            ><i class="ki-filled ki-coffee"></i>Get Started</a
                                                        >
                                                    </li>
                                                    <li>
                                                        <a
                                                            class="kt-dropdown-menu-link"
                                                            href="account/home/user-profile.html"
                                                            ><i class="ki-filled ki-some-files"></i>My Profile</a
                                                        >
                                                    </li>
                                                    <li>
                                                        <a class="kt-dropdown-menu-link" href="#"
                                                            ><span class="flex items-center gap-2"
                                                                ><i class="ki-filled ki-icon"></i>Billing</span
                                                            ><span
                                                                class="ms-auto inline-flex items-center"
                                                                data-kt-tooltip="true"
                                                                data-kt-tooltip-placement="top"
                                                                ><i
                                                                    class="ki-filled ki-information-2 text-base text-muted-foreground"
                                                                ></i
                                                                ><span class="kt-tooltip" data-kt-tooltip-content="true"
                                                                    >Payment and subscription info</span
                                                                ></span
                                                            ></a
                                                        >
                                                    </li>
                                                    <li>
                                                        <a
                                                            class="kt-dropdown-menu-link"
                                                            href="account/security/overview.html"
                                                            ><i class="ki-filled ki-medal-star"></i>Security</a
                                                        >
                                                    </li>
                                                    <li>
                                                        <a
                                                            class="kt-dropdown-menu-link"
                                                            href="account/members/teams.html"
                                                            ><i class="ki-filled ki-setting"></i>Members &amp; Roles</a
                                                        >
                                                    </li>
                                                    <li>
                                                        <a
                                                            class="kt-dropdown-menu-link"
                                                            href="account/integrations.html"
                                                            ><i class="ki-filled ki-switch"></i>Integrations</a
                                                        >
                                                    </li>
                                                    <li><div class="kt-dropdown-menu-separator"></div></li>
                                                    <li>
                                                        <a
                                                            class="kt-dropdown-menu-link"
                                                            href="account/security/overview.html"
                                                            ><span class="flex items-center gap-2"
                                                                ><i class="ki-filled ki-shield-tick"></i
                                                                >Notifications</span
                                                            ><input
                                                                checked=""
                                                                class="ms-auto kt-switch"
                                                                name="check"
                                                                type="checkbox"
                                                                value="1"
                                                        /></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li>
                                            <a class="kt-dropdown-menu-link" href="https://devs.keenthemes.com/"
                                                ><i class="ki-filled ki-message-programming"></i>Dev Forum</a
                                            >
                                        </li>
                                        <li
                                            data-kt-dropdown="true"
                                            data-kt-dropdown-placement="right-start"
                                            data-kt-dropdown-trigger="hover"
                                        >
                                            <button class="kt-dropdown-menu-toggle py-1" data-kt-dropdown-toggle="true">
                                                <span class="flex items-center gap-2"
                                                    ><i class="ki-filled ki-icon"></i>Language</span
                                                ><span class="ms-auto kt-badge kt-badge-stroke shrink-0"
                                                    >English
                                                    <img
                                                        alt=""
                                                        class="inline-block size-3.5 rounded-full"
                                                        src="{{ asset('/public/hotel-management-admin/') }}/media/flags/united-states.svg"
                                                /></span>
                                            </button>
                                            <div class="kt-dropdown-menu w-[180px]" data-kt-dropdown-menu="true">
                                                <ul class="kt-dropdown-menu-sub">
                                                    <li class="active">
                                                        <a class="kt-dropdown-menu-link" href="indexe3ae.html?dir=ltr"
                                                            ><span class="flex items-center gap-2"
                                                                ><img
                                                                    alt=""
                                                                    class="inline-block size-4 rounded-full"
                                                                    src="{{ asset('/public/hotel-management-admin/') }}/media/flags/united-states.svg"
                                                                /><span class="kt-menu-title">English</span></span
                                                            ><i
                                                                class="ki-solid ki-check-circle ms-auto text-green-500 text-base"
                                                            ></i
                                                        ></a>
                                                    </li>
                                                    <li class="">
                                                        <a class="kt-dropdown-menu-link" href="index347d.html?dir=rtl"
                                                            ><span class="flex items-center gap-2"
                                                                ><img
                                                                    alt=""
                                                                    class="inline-block size-4 rounded-full"
                                                                    src="{{ asset('/public/hotel-management-admin/') }}/media/flags/saudi-arabia.svg"
                                                                /><span class="kt-menu-title">Arabic(Saudi)</span></span
                                                            ></a
                                                        >
                                                    </li>
                                                    <li class="">
                                                        <a class="kt-dropdown-menu-link" href="indexe3ae.html?dir=ltr"
                                                            ><span class="flex items-center gap-2"
                                                                ><img
                                                                    alt=""
                                                                    class="inline-block size-4 rounded-full"
                                                                    src="{{ asset('/public/hotel-management-admin/') }}/media/flags/spain.svg"
                                                                /><span class="kt-menu-title">Spanish</span></span
                                                            ></a
                                                        >
                                                    </li>
                                                    <li class="">
                                                        <a class="kt-dropdown-menu-link" href="indexe3ae.html?dir=ltr"
                                                            ><span class="flex items-center gap-2"
                                                                ><img
                                                                    alt=""
                                                                    class="inline-block size-4 rounded-full"
                                                                    src="{{ asset('/public/hotel-management-admin/') }}/media/flags/germany.svg"
                                                                /><span class="kt-menu-title">German</span></span
                                                            ></a
                                                        >
                                                    </li>
                                                    <li class="">
                                                        <a class="kt-dropdown-menu-link" href="indexe3ae.html?dir=ltr"
                                                            ><span class="flex items-center gap-2"
                                                                ><img
                                                                    alt=""
                                                                    class="inline-block size-4 rounded-full"
                                                                    src="{{ asset('/public/hotel-management-admin/') }}/media/flags/japan.svg"
                                                                /><span class="kt-menu-title">Japanese</span></span
                                                            ></a
                                                        >
                                                    </li>
                                                </ul>
                                            </div>
                                        </li>
                                        <li><div class="kt-dropdown-menu-separator"></div></li>
                                    </ul>
                                    <div class="px-2.5 pt-1.5 mb-2.5 flex flex-col gap-3.5">
                                        <div class="flex items-center gap-2 justify-between">
                                            <span class="flex items-center gap-2"
                                                ><i class="ki-filled ki-moon text-base text-muted-foreground"></i
                                                ><span class="font-medium text-2sm">Dark Mode</span></span
                                            ><input
                                                class="kt-switch"
                                                data-kt-theme-switch-state="dark"
                                                data-kt-theme-switch-toggle="true"
                                                name="check"
                                                type="checkbox"
                                                value="1"
                                            />
                                        </div>
                                        <a
                                            class="kt-btn kt-btn-outline justify-center w-full"
                                            href="authentication/classic/sign-in.html"
                                            >Log out</a
                                        >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>
                <main class="h-full" id="content" role="content">
                    <!-- <div class="kt-container-fixed" id="contentContainer">@yield('content')</div> -->
                     @yield('content')
                </main>
                <footer class="kt-footer kt-footer-fixed">
                    <div class="kt-container-fixed">
                        <div
                            class="flex flex-col md:flex-row justify-center md:justify-between items-center gap-3 py-5"
                        >
                            <div class="flex order-2 md:order-1 gap-2 font-normal text-sm">
                                <span class="text-secondary-foreground">2026©</span
                                ><a class="text-secondary-foreground hover:text-primary" href="https://keenthemes.com/"
                                    >Keenthemes Inc.</a
                                >
                            </div>
                            <nav class="flex order-1 md:order-2 gap-4 font-normal text-sm text-secondary-foreground">
                                <a class="hover:text-primary" href="https://keenthemes.com/metronic/tailwind/docs"
                                    >Docs</a
                                ><a class="hover:text-primary" href="https://1.envato.market/Vm7VRE">Purchase</a
                                ><a
                                    class="hover:text-primary"
                                    href="https://keenthemes.com/metronic/tailwind/docs/getting-started/license"
                                    >FAQ</a
                                ><a class="hover:text-primary" href="https://devs.keenthemes.com/">Support</a
                                ><a
                                    class="hover:text-primary"
                                    href="https://keenthemes.com/metronic/tailwind/docs/getting-started/license"
                                    >License</a
                                >
                            </nav>
                        </div>
                    </div>
                </footer>
            </div>
        </div>
        <div class="kt-modal" data-kt-modal="true" id="search_modal">
            <div class="kt-modal-content max-w-[600px] top-[15%]">
                <div class="kt-modal-header py-4 px-5">
                    <i class="ki-filled ki-magnifier text-muted-foreground text-xl"></i
                    ><input
                        class="kt-input kt-input-ghost"
                        name="query"
                        placeholder="Tap to start search"
                        type="text"
                        value=""
                    /><button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-dim shrink-0" data-kt-modal-dismiss="true">
                        <i class="ki-filled ki-cross"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="kt-modal" data-kt-modal="true" id="share_profile_modal">
            <div class="kt-modal-content max-w-[500px] top-5 lg:top-[15%]">
                <div class="kt-modal-header">
                    <h3 class="kt-modal-title">Share Profile</h3>
                    <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true">
                        <i class="ki-filled ki-cross"></i>
                    </button>
                </div>
                <div class="kt-modal-body grid gap-5 px-0 py-5">
                    <div class="flex flex-col px-5 gap-2.5">
                        <div class="flex flex-center gap-1">
                            <label class="text-mono font-semibold text-sm">Share read-only link</label
                            ><i class="ki-filled ki-information-2 text-muted-foreground text-sm"></i>
                        </div>
                        <label class="kt-input"
                            ><input type="text" value="https://metronic.com/profiles/x7g2vA3kZ5" /><button
                                class="kt-btn kt-btn-icon kt-btn-sm kt-btn-ghost -me-2"
                            >
                                <i class="ki-filled ki-copy"></i></button
                        ></label>
                    </div>
                    <div class="border-b border-b-border"></div>
                    <div class="flex flex-col px-5 gap-2.5">
                        <div class="flex flex-center gap-1">
                            <label class="text-mono font-semibold text-sm">Share via email</label
                            ><i class="ki-filled ki-information-2 text-muted-foreground text-sm"></i>
                        </div>
                        <div class="flex flex-center gap-2.5">
                            <label class="kt-input"><input type="text" value="miles.turner@gmail.com" /></label
                            ><button class="kt-btn kt-btn-primary">Share</button>
                        </div>
                    </div>
                    <div class="kt-scrollable-y-auto max-h-[300px]">
                        <div class="flex flex-col px-5 gap-3">
                            <div class="flex items-center flex-wrap gap-2">
                                <div class="flex items-center grow gap-2.5">
                                    <img
                                        alt=""
                                        class="rounded-full size-9 shrink-0"
                                        src="{{ asset('/public/hotel-management-admin/') }}/media/avatars/300-3.png"
                                    />
                                    <div class="flex flex-col">
                                        <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#"
                                            >Tyler Hero</a
                                        ><a class="hover:text-primary text-2sm text-secondary-foreground" href="#"
                                            >tyler.hero@gmail.com</a
                                        >
                                    </div>
                                </div>
                                <select class="kt-select max-w-24" data-kt-select="true">
                                    <option selected="">Owner</option>
                                    <option>Editor</option>
                                    <option>Viewer</option>
                                </select>
                            </div>
                            <div class="flex items-center flex-wrap gap-2">
                                <div class="flex items-center grow gap-2.5">
                                    <img
                                        alt=""
                                        class="rounded-full size-9 shrink-0"
                                        src="{{ asset('/public/hotel-management-admin/') }}/media/avatars/300-1.png"
                                    />
                                    <div class="flex flex-col">
                                        <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#"
                                            >Esther Howard</a
                                        ><a class="hover:text-primary text-2sm text-secondary-foreground" href="#"
                                            >esther.howard@gmail.com</a
                                        >
                                    </div>
                                </div>
                                <select class="kt-select max-w-24" data-kt-select="true">
                                    <option>Owner</option>
                                    <option selected="">Editor</option>
                                    <option>Viewer</option>
                                </select>
                            </div>
                            <div class="flex items-center flex-wrap gap-2">
                                <div class="flex items-center grow gap-2.5">
                                    <img
                                        alt=""
                                        class="rounded-full size-9 shrink-0"
                                        src="{{ asset('/public/hotel-management-admin/') }}/media/avatars/300-11.png"
                                    />
                                    <div class="flex flex-col">
                                        <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#"
                                            >Jacob Jones</a
                                        ><a class="hover:text-primary text-2sm text-secondary-foreground" href="#"
                                            >jacob.jones@gmail.com</a
                                        >
                                    </div>
                                </div>
                                <select class="kt-select max-w-24" data-kt-select="true">
                                    <option>Owner</option>
                                    <option>Editor</option>
                                    <option selected="">Viewer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-b-border"></div>
                    <div class="flex flex-col px-5 gap-4">
                        <label class="text-mono font-semibold text-sm">Settings</label>
                        <div class="flex flex-center justify-between flex-wrap gap-2">
                            <div class="flex flex-center gap-1.5">
                                <i class="ki-filled ki-user text-muted-foreground"></i>
                                <div class="flex flex-center text-secondary-foreground font-medium text-xs">
                                    Anyone at<a class="text-xs font-medium link mx-1" href="#">KeenThemes</a>can view
                                </div>
                            </div>
                            <button class="kt-link kt-link-sm kt-link-underlined kt-link-dashed">Change Access</button>
                        </div>
                        <div class="flex flex-center justify-between flex-wrap gap-2 mb-2.5">
                            <div class="flex flex-center gap-1.5">
                                <i class="ki-filled ki-icon text-muted-foreground"></i>
                                <div class="flex flex-center text-secondary-foreground font-medium text-xs">
                                    Anyone with link can edit
                                </div>
                            </div>
                            <button class="kt-link kt-link-sm kt-link-underlined kt-link-dashed">Set Password</button>
                        </div>
                        <button class="kt-btn kt-btn-primary justify-center">Done</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-modal" data-kt-modal="true" id="give_award_modal">
            <div class="kt-modal-content max-w-[500px] top-[15%]">
                <div class="kt-modal-header pr-2.5">
                    <h3 class="kt-modal-title">Give Award</h3>
                    <button class="kt-btn kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true">
                        <i class="ki-filled ki-black-left"></i>
                    </button>
                </div>
                <div class="kt-modal-body grid gap-5 px-0 py-5">
                    <div class="flex flex-col px-5 gap-2.5">
                        <div class="flex flex-center gap-1">
                            <label class="text-mono font-semibold text-sm">Share read-only link</label
                            ><i class="ki-filled ki-information-2 text-muted-foreground text-sm"></i>
                        </div>
                        <label class="kt-input"
                            ><input type="text" value="https://metronic.com/profiles/x7g2vA3kZ5" /><button
                                class="kt-btn kt-btn-icon kt-btn-sm kt-btn-ghost -me-2"
                            >
                                <i class="ki-filled ki-copy"></i></button
                        ></label>
                    </div>
                    <div class="border-b border-b-border"></div>
                    <div class="flex flex-col px-5 gap-2.5">
                        <div class="flex flex-center gap-1">
                            <label class="text-mono font-semibold text-sm">Share via email</label
                            ><i class="ki-filled ki-information-2 text-muted-foreground text-sm"></i>
                        </div>
                        <div class="flex flex-center gap-2.5">
                            <label class="kt-input"><input type="text" value="miles.turner@gmail.com" /></label
                            ><button class="kt-btn kt-btn-primary">Share</button>
                        </div>
                    </div>
                    <div class="kt-scrollable-y-auto max-h-[300px]">
                        <div class="flex flex-col px-5 gap-3">
                            <div class="flex items-center flex-wrap gap-2">
                                <div class="flex items-center grow gap-2.5">
                                    <img
                                        alt=""
                                        class="rounded-full size-9 shrink-0"
                                        src="{{ asset('/public/hotel-management-admin/') }}/media/avatars/300-3.png"
                                    />
                                    <div class="flex flex-col">
                                        <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#"
                                            >Tyler Hero</a
                                        ><a class="hover:text-primary text-2sm text-secondary-foreground" href="#"
                                            >tyler.hero@gmail.com</a
                                        >
                                    </div>
                                </div>
                                <select class="kt-select max-w-24" data-kt-select="true">
                                    <option selected="">Owner</option>
                                    <option>Editor</option>
                                    <option>Viewer</option>
                                </select>
                            </div>
                            <div class="flex items-center flex-wrap gap-2">
                                <div class="flex items-center grow gap-2.5">
                                    <img
                                        alt=""
                                        class="rounded-full size-9 shrink-0"
                                        src="{{ asset('/public/hotel-management-admin/') }}/media/avatars/300-1.png"
                                    />
                                    <div class="flex flex-col">
                                        <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#"
                                            >Esther Howard</a
                                        ><a class="hover:text-primary text-2sm text-secondary-foreground" href="#"
                                            >esther.howard@gmail.com</a
                                        >
                                    </div>
                                </div>
                                <select class="kt-select max-w-24" data-kt-select="true">
                                    <option>Owner</option>
                                    <option selected="">Editor</option>
                                    <option>Viewer</option>
                                </select>
                            </div>
                            <div class="flex items-center flex-wrap gap-2">
                                <div class="flex items-center grow gap-2.5">
                                    <img
                                        alt=""
                                        class="rounded-full size-9 shrink-0"
                                        src="{{ asset('/public/hotel-management-admin/') }}/media/avatars/300-11.png"
                                    />
                                    <div class="flex flex-col">
                                        <a class="text-sm font-semibold text-mono hover:text-primary mb-px" href="#"
                                            >Jacob Jones</a
                                        ><a class="hover:text-primary text-2sm text-secondary-foreground" href="#"
                                            >jacob.jones@gmail.com</a
                                        >
                                    </div>
                                </div>
                                <select class="kt-select max-w-24" data-kt-select="true">
                                    <option>Owner</option>
                                    <option>Editor</option>
                                    <option selected="">Viewer</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-b-border"></div>
                    <div class="flex flex-col px-5 gap-4">
                        <label class="text-mono font-semibold text-sm">Settings</label>
                        <div class="flex flex-center justify-between flex-wrap gap-2">
                            <div class="flex flex-center gap-1.5">
                                <i class="ki-filled ki-user text-muted-foreground"></i>
                                <div class="flex flex-center text-secondary-foreground font-medium text-xs">
                                    Anyone at<a class="text-xs font-medium link mx-1" href="#">KeenThemes</a>can view
                                </div>
                            </div>
                            <button class="kt-link kt-link-sm kt-link-underlined kt-link-dashed">Change Access</button>
                        </div>
                        <div class="flex flex-center justify-between flex-wrap gap-2 mb-2.5">
                            <div class="flex flex-center gap-1.5">
                                <i class="ki-filled ki-icon text-muted-foreground"></i>
                                <div class="flex flex-center text-secondary-foreground font-medium text-xs">
                                    Anyone with link can edit
                                </div>
                            </div>
                            <button class="kt-link kt-link-sm kt-link-underlined kt-link-dashed">Set Password</button>
                        </div>
                        <button class="kt-btn kt-btn-primary justify-center">Done</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="kt-modal" data-kt-modal="true" id="report_user_modal">
            <div class="kt-modal-content max-w-[500px] top-[15%]">
                <div class="kt-modal-header">
                    <h3 class="kt-modal-title">Report User</h3>
                    <button class="kt-btn kt-btn-sm kt-btn-icon kt-btn-ghost shrink-0" data-kt-modal-dismiss="true">
                        <i class="ki-filled ki-cross"></i>
                    </button>
                </div>
                <div class="kt-modal-body p-0">
                    <div class="p-5">
                        <div class="grid place-items-center gap-1">
                            <div class="flex justify-center items-center rounded-full">
                                <img
                                    class="rounded-full max-h-[55px] max-w-full"
                                    src="{{ asset('/public/hotel-management-admin/') }}/media/avatars/300-1.png"
                                />
                            </div>
                            <div class="flex items-center justify-center gap-1">
                                <a class="hover:text-primary text-sm leading-5 font-semibold text-mono" href="#"
                                    >Jenny Klabber</a
                                ><svg
                                    class="text-primary"
                                    fill="none"
                                    height="13"
                                    viewbox="0 0 15 16"
                                    width="13"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        d="M14.5425 6.89749L13.5 5.83999C13.4273 5.76877 13.3699 5.6835 13.3312 5.58937C13.2925 5.49525 13.2734 5.39424 13.275 5.29249V3.79249C13.274 3.58699 13.2324 3.38371 13.1527 3.19432C13.0729 3.00494 12.9565 2.83318 12.8101 2.68892C12.6638 2.54466 12.4904 2.43073 12.2998 2.35369C12.1093 2.27665 11.9055 2.23801 11.7 2.23999H10.2C10.0982 2.24159 9.99722 2.22247 9.9031 2.18378C9.80898 2.1451 9.72371 2.08767 9.65249 2.01499L8.60249 0.957487C8.30998 0.665289 7.91344 0.50116 7.49999 0.50116C7.08654 0.50116 6.68999 0.665289 6.39749 0.957487L5.33999 1.99999C5.26876 2.07267 5.1835 2.1301 5.08937 2.16879C4.99525 2.20747 4.89424 2.22659 4.79249 2.22499H3.29249C3.08699 2.22597 2.88371 2.26754 2.69432 2.34731C2.50494 2.42709 2.33318 2.54349 2.18892 2.68985C2.04466 2.8362 1.93073 3.00961 1.85369 3.20013C1.77665 3.39064 1.73801 3.5945 1.73999 3.79999V5.29999C1.74159 5.40174 1.72247 5.50275 1.68378 5.59687C1.6451 5.691 1.58767 5.77627 1.51499 5.84749L0.457487 6.89749C0.165289 7.19 0.00115967 7.58654 0.00115967 7.99999C0.00115967 8.41344 0.165289 8.80998 0.457487 9.10249L1.49999 10.16C1.57267 10.2312 1.6301 10.3165 1.66878 10.4106C1.70747 10.5047 1.72659 10.6057 1.72499 10.7075V12.2075C1.72597 12.413 1.76754 12.6163 1.84731 12.8056C1.92709 12.995 2.04349 13.1668 2.18985 13.3111C2.3362 13.4553 2.50961 13.5692 2.70013 13.6463C2.89064 13.7233 3.0945 13.762 3.29999 13.76H4.79999C4.90174 13.7584 5.00275 13.7775 5.09687 13.8162C5.191 13.8549 5.27627 13.9123 5.34749 13.985L6.40499 15.0425C6.69749 15.3347 7.09404 15.4988 7.50749 15.4988C7.92094 15.4988 8.31748 15.3347 8.60999 15.0425L9.65999 14C9.73121 13.9273 9.81647 13.8699 9.9106 13.8312C10.0047 13.7925 10.1057 13.7734 10.2075 13.775H11.7075C12.1212 13.775 12.518 13.6106 12.8106 13.3181C13.1031 13.0255 13.2675 12.6287 13.2675 12.215V10.715C13.2659 10.6132 13.285 10.5122 13.3237 10.4181C13.3624 10.324 13.4198 10.2387 13.4925 10.1675L14.55 9.10999C14.6953 8.96452 14.8104 8.79176 14.8887 8.60164C14.9671 8.41152 15.007 8.20779 15.0063 8.00218C15.0056 7.79656 14.9643 7.59311 14.8847 7.40353C14.8051 7.21394 14.6888 7.04197 14.5425 6.89749ZM10.635 6.64999L6.95249 10.25C6.90055 10.3026 6.83864 10.3443 6.77038 10.3726C6.70212 10.4009 6.62889 10.4153 6.55499 10.415C6.48062 10.4139 6.40719 10.3982 6.33896 10.3685C6.27073 10.3389 6.20905 10.2961 6.15749 10.2425L4.37999 8.44249C4.32532 8.39044 4.28169 8.32793 4.25169 8.25867C4.22169 8.18941 4.20593 8.11482 4.20536 8.03934C4.20479 7.96387 4.21941 7.88905 4.24836 7.81934C4.27731 7.74964 4.31999 7.68647 4.37387 7.63361C4.42774 7.58074 4.4917 7.53926 4.56194 7.51163C4.63218 7.484 4.70726 7.47079 4.78271 7.47278C4.85816 7.47478 4.93244 7.49194 5.00112 7.52324C5.0698 7.55454 5.13148 7.59935 5.18249 7.65499L6.56249 9.05749L9.84749 5.84749C9.95296 5.74215 10.0959 5.68298 10.245 5.68298C10.394 5.68298 10.537 5.74215 10.6425 5.84749C10.6953 5.90034 10.737 5.96318 10.7653 6.03234C10.7935 6.1015 10.8077 6.1756 10.807 6.25031C10.8063 6.32502 10.7908 6.39884 10.7612 6.46746C10.7317 6.53608 10.6888 6.59813 10.635 6.64999Z"
                                        fill="currentColor"
                                    ></path>
                                </svg>
                            </div>
                        </div>
                    </div>
                    <div class="border-b border-b-border"></div>
                    <div class="flex flex-col gap-5 p-5">
                        <div class="text-sm text-mono font-semibold">Let us know why you’re reporing this person</div>
                        <div class="flex flex-col gap-3.5">
                            <label class="kt-form-label flex items-center gap-2.5"
                                ><input
                                    checked=""
                                    class="kt-radio radio-sm"
                                    name="report-option"
                                    type="radio"
                                    value="1"
                                />
                                <div class="flex flex-col gap-0.5">
                                    <div class="text-sm font-semibold text-mono">Impersonation</div>
                                    <div class="text-sm font-medium text-secondary-foreground">
                                        It looks like this profile might be impersonating someone else
                                    </div>
                                </div></label
                            ><label class="kt-form-label flex items-center gap-2.5"
                                ><input
                                    checked=""
                                    class="kt-radio radio-sm"
                                    name="report-option"
                                    type="radio"
                                    value="2"
                                />
                                <div class="flex flex-col gap-0.5">
                                    <div class="text-sm font-semibold text-mono">Spammy</div>
                                    <div class="text-sm font-medium text-secondary-foreground">
                                        This person profile, comments or posts contain misleading text
                                    </div>
                                </div></label
                            ><label class="kt-form-label flex items-center gap-2.5"
                                ><input
                                    checked=""
                                    class="kt-radio radio-sm"
                                    name="report-option"
                                    type="radio"
                                    value="3"
                                />
                                <div class="flex flex-col gap-0.5">
                                    <div class="text-sm font-semibold text-mono">Off bumble behavior</div>
                                    <div class="text-sm font-medium text-secondary-foreground">
                                        This person has engaged in behavior that is abusive, bullying
                                    </div>
                                </div></label
                            ><label class="kt-form-label flex items-center gap-2.5"
                                ><input
                                    checked=""
                                    class="kt-radio radio-sm"
                                    name="report-option"
                                    type="radio"
                                    value="4"
                                />
                                <div class="flex flex-col gap-0.5">
                                    <div class="text-sm font-semibold text-mono">Something else</div>
                                    <div class="text-sm font-medium text-secondary-foreground">
                                        None of the reasons listed above are suitable
                                    </div>
                                </div></label
                            >
                        </div>
                    </div>
                    <div class="border-b border-b-border"></div>
                    <div class="text-2sm font-medium text-center text-foreground p-5">
                        Don't worry, your report is completely anonymous; the person you're<br />reporting will not be
                        informed that you've submitted it
                    </div>
                    <div class="border-b border-b-border"></div>
                    <div class="flex items-center gap-2.5 justify-end p-5" id="report_user_modal">
                        <button class="kt-btn kt-btn-primary">Report this person</button
                        ><button class="kt-btn kt-btn-outline" data-kt-modal-dismiss="true">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="{{ asset('/public/hotel-management-admin/') }}/js/core.bundle.js"></script>
        <script src="{{ asset('/public/hotel-management-admin/') }}/vendors/ktui/ktui.min.js"></script>
        <script src="{{ asset('/public/hotel-management-admin/') }}/vendors/apexcharts/apexcharts.min.js"></script>
        <script src="{{ asset('/public/hotel-management-admin/') }}/js/general.js"></script>
        <script src="{{ asset('/public/hotel-management-admin/') }}/js/demo1.js"></script>

        <script>
            (function () {
                const footer = document.querySelector('.kt-footer.kt-footer-fixed');
                if (!footer) return;

                const root = document.documentElement;
                const update = () => {
                    const height = Math.ceil(footer.getBoundingClientRect().height || 0);
                    root.style.setProperty('--kt-footer-height', `${height}px`);
                };

                update();
                window.addEventListener('load', update);
                window.addEventListener('resize', update);
                window.addEventListener('orientationchange', update);

                if ('ResizeObserver' in window) {
                    const ro = new ResizeObserver(update);
                    ro.observe(footer);
                }
            })();
        </script>
        @stack('scripts')
    </body>
</html>
