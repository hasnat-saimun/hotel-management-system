@extends('frontend.layout')
@push('styles')
<style>
.step-active{
  
  color: rgba(255, 250, 250, 0.6);
}

  
.tab-circle{
    background-color: #fff;
    color:#000
}


.step {
    padding: 10px 20px;
    background: #ddd;
    border-radius: 20px;
    cursor: pointer;
}

.step.active {
    background: #007bff;
    color: white;
}

.tab{    
    display: none;
}


.tab.active {
    display: block;
}

</style>


@endpush
@section('content')

  <!-- STEPS BAR -->
<section class=" bg-[#f9fbff] text-slate-700 py-10">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">

        <div class="bg-[#d6cec3] px-10 py-4">
        <div class="flex items-center justify-between text-sm font-semibold text-white">

            <!-- Step 1 -->
            <div class="step-link flex items-center gap-3 tab1" id="firstTab">
            <span class="flex h-7 w-7 items-center justify-center rounded-full tab-circle text-xs font-bold">
                1
            </span>
            <span>Rooms and Rates</span>
            </div>

            <!-- Divider -->
            <div class="flex-1 mx-4 h-px bg-white/40"></div>

            <!-- Step 2 (Active) -->
            <div class="step-link flex items-center gap-3 rounded-tr-lg tab2">
            <span class="flex h-7 w-7 items-center justify-center rounded-full  tab-circle text-xs font-bold">
                2
            </span>
            <span>Guest Details</span>
            </div>

            <!-- Divider -->
            <div class="flex-1 mx-4 h-px bg-white/40"></div>

            <!-- Step 3 -->
            <div class="step-link flex items-center gap-3 tab3">
            <span class="flex h-7 w-7 items-center justify-center rounded-full tab-circle text-xs font-bold">
                3
            </span>
            <span>Confirmation</span>
            </div>

        </div>
        </div>

            <!-- Tab Content -->
            <div class="mt-6 tab active" id="tab1">
                <p class="mt-4 text-sm leading-6 text-slate-500 max-w-xl ">
                    Includes a la carte breakfast daily, all non-alcoholic beverages at qualia,
                    
                </p>
            </div>
            <div class="mt-4 flex items-center gap-3 tab" id="tab2">
                <p class="mt-4 text-sm leading-6 text-slate-500 max-w-xl">
                    Includes a la carte breakfast daily, all non-alcoholic beverages at qualia,
                    use of a golf buggy for the duration of your stay and more.
                </p>
            </div>
            <div class="mt-4 flex items-center gap-3 tab" id="tab3">
                <p class="mt-4 text-sm leading-6 text-slate-500 max-w-xl">
                    Includes a la carte breakfast daily, all non-alcoholic beverages at qualia,
                    use of a golf buggy for the duration of your stay and more.
                    Includes a la carte breakfast daily, all non-alcoholic beverages at qualia,
                    use of a golf buggy for the duration of your stay and more.
                </p>
            </div>
        <!-- button -->
         <div class="mt-6 flex items-center gap-3 ">
            <button  type="button" class=" step active">1st</button>

            <button type="button" class=" step">2nd</button>

            <button type="button" class=" step">3rd</button>
        </div>

    </div>

    
       



</section>




@endsection


