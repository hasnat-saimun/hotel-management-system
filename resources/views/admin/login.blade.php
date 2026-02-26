<!--
Product: Metronic is a toolkit of UI components built with Tailwind CSS for developing scalable web applications quickly and efficiently
Version: v9.3.8
Author: Keenthemes
Contact: support@keenthemes.com
Website: https://www.keenthemes.com
Support: https://devs.keenthemes.com
Follow: https://www.twitter.com/keenthemes
License: https://keenthemes.com/metronic/tailwind/docs/getting-started/license
-->
<!DOCTYPE html>
<html class="h-full" data-kt-theme="true" data-kt-theme-mode="light" dir="ltr" lang="en">
 
<!-- Mirrored from keenthemes.com/metronic/tailwind/demo1/authentication/classic/sign-in/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 21 Jan 2026 08:01:04 GMT -->
<!-- Added by HTTrack --><meta http-equiv="content-type" content="text/html;charset=utf-8" /><!-- /Added by HTTrack -->
<head>
  <title>
   Metronic - Tailwind CSS Sign In
  </title>
  <meta charset="utf-8"/>
  <meta content="follow, index" name="robots"/>
  <link href="index.html" rel="canonical"/>
  <meta content="width=device-width, initial-scale=1, shrink-to-fit=no" name="viewport"/>
  <meta content="Metronic - Tailwind CSS Sign In" property="og:title"/>
  
  <link href="https://keenthemes.com/metronic/tailwind/dist/assets/media/app/favicon.ico" rel="shortcut icon"/>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet"/>
  <link href="{{ asset('/public/hotel-management-admin/') }}/vendors/apexcharts/apexcharts.css" rel="stylesheet"/>
  <link href="{{ asset('/public/hotel-management-admin/') }}/vendors/keenicons/styles.bundle.css" rel="stylesheet"/>
  <link href="{{ asset('/public/hotel-management-admin/') }}/css/styles.css" rel="stylesheet"/>
  <!-- Google tag (gtag.js) -->
  <script async="" src="https://www.googletagmanager.com/gtag/js?id=G-52YZ3XGZJ6">
  </script>
  <script>
   window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-52YZ3XGZJ6');
  </script>
 </head>
 <body class="antialiased flex h-full text-base text-foreground bg-background">
  <!-- Theme Mode -->
  <script>
   const defaultThemeMode = 'light'; // light|dark|system
			let themeMode;

			if (document.documentElement) {
				if (localStorage.getItem('kt-theme')) {
					themeMode = localStorage.getItem('kt-theme');
				} else if (
					document.documentElement.hasAttribute('data-kt-theme-mode')
				) {
					themeMode =
						document.documentElement.getAttribute('data-kt-theme-mode');
				} else {
					themeMode = defaultThemeMode;
				}

				if (themeMode === 'system') {
					themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches
						? 'dark'
						: 'light';
				}

				document.documentElement.classList.add(themeMode);
			}
  </script>
  <!-- End of Theme Mode -->
  <!-- Page -->
  <style>
   .page-bg {
			background-image: url('{{ asset('/public/hotel-management-admin/') }}/media/images/2600x1200/bg-10.png');
		}
		.dark .page-bg {
			background-image: url('{{ asset('/public/hotel-management-admin/') }}/media/images/2600x1200/bg-10-dark.png');
		}
  </style>
  <div class="flex items-center justify-center grow bg-center bg-no-repeat page-bg">
   <div class="kt-card max-w-[370px] w-full">
    <form action="{{ route('admin.login.post') }}" method="POST" class="kt-card-content flex flex-col gap-5 p-10" id="sign_in_form">
     @csrf
     <div class="text-center mb-2.5">
      <h3 class="text-lg font-medium text-mono leading-none mb-2.5">
       Sign in
      </h3>
      <div class="flex items-center justify-center font-medium">
       <span class="text-sm text-secondary-foreground me-1.5">
        Need an account?
       </span>
       <a class="text-sm link" href="../sign-up/index.html">
        Sign up
       </a>
      </div>
     </div>
     <div class="flex items-center gap-2">
      <span class="border-t border-border w-full">
      </span>
      <span class="border-t border-border w-full">
      </span>
     </div>
     <div class="flex flex-col gap-1">
      <label class="kt-form-label font-normal text-mono">
       Email
      </label>
      <input class="kt-input" name="email" placeholder="email@email.com" type="text" value="{{ old('email') }}"/>
     </div>
     <div class="flex flex-col gap-1">
      <div class="flex items-center justify-between gap-1">
       <label class="kt-form-label font-normal text-mono">
        Password
       </label>
       <a class="text-sm kt-link shrink-0" href="../reset-password/enter-email/index.html">
        Forgot Password?
       </a>
      </div>
      <div class="kt-input" data-kt-toggle-password="true">
      <input name="password" placeholder="Enter Password" type="password" value=""/>
       <button class="kt-btn kt-btn-sm kt-btn-ghost kt-btn-icon bg-transparent! -me-1.5" data-kt-toggle-password-trigger="true" type="button">
        <span class="kt-toggle-password-active:hidden">
         <i class="ki-filled ki-eye text-muted-foreground">
         </i>
        </span>
        <span class="hidden kt-toggle-password-active:block">
         <i class="ki-filled ki-eye-slash text-muted-foreground">
         </i>
        </span>
       </button>
      </div>
     </div>
     <label class="kt-label">
      <input class="kt-checkbox kt-checkbox-sm" name="check" type="checkbox" value="1"/>
      <span class="kt-checkbox-label">
       Remember me
      </span>
     </label>
    <button type="submit" class="kt-btn kt-btn-primary flex justify-center grow">
     Sign In
    </button>
    </form>
   </div>
  </div>
  <!-- End of Page -->
  <!-- Scripts -->
  <script src="{{ asset('/public/hotel-management-admin/') }}/js/core.bundle.js">
  </script>
  <script src="{{ asset('/public/hotel-management-admin/') }}/vendors/ktui/ktui.min.js">
  </script>
  <script src="{{ asset('/public/hotel-management-admin/') }}/vendors/apexcharts/apexcharts.min.js">
  </script>
  <!-- End of Scripts -->
 </body>

<!-- Mirrored from keenthemes.com/metronic/tailwind/demo1/authentication/classic/sign-in/ by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 21 Jan 2026 08:01:04 GMT -->
</html>
