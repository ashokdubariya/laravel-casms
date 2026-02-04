<!-- Installation Progress Bar -->
<div class="mb-8">
    <div class="flex justify-between items-center">
        @php
            $steps = [
                1 => ['name' => 'Welcome', 'icon' => 'fa-flag'],
                2 => ['name' => 'Requirements', 'icon' => 'fa-server'],
                3 => ['name' => 'Database', 'icon' => 'fa-database'],
                4 => ['name' => 'Migration', 'icon' => 'fa-cogs'],
                5 => ['name' => 'Admin', 'icon' => 'fa-user-shield'],
                6 => ['name' => 'Complete', 'icon' => 'fa-check-circle'],
            ];
            $currentStep = $step ?? 1;
        @endphp

        @foreach($steps as $stepNum => $stepInfo)
            <div class="flex flex-col items-center {{ $loop->last ? '' : 'flex-1' }}">
                <div class="relative">
                    <!-- Step Circle -->
                    <div class="w-14 h-14 rounded-full flex items-center justify-center font-medium transition-all duration-500 shadow-lg
                        @if($stepNum < $currentStep) 
                            bg-gradient-to-br from-approval-green to-green-600 text-white transform scale-100
                        @elseif($stepNum == $currentStep) 
                            bg-gradient-to-br from-[#1a425f] to-[#0f2d42] text-white ring-4 ring-[#1a425f]/30 transform scale-110 animate-pulse
                        @else 
                            bg-gray-200 text-gray-400 transform scale-95
                        @endif">
                        @if($stepNum < $currentStep)
                            <i class="fas fa-check text-xl"></i>
                        @else
                            <i class="fas {{ $stepInfo['icon'] }} text-base"></i>
                        @endif
                    </div>
                    
                    <!-- Connector Line with Gradient -->
                    @if(!$loop->last)
                        <div class="absolute top-7 left-14 h-1 transition-all duration-500 rounded-full overflow-hidden"
                             style="width: calc(100vw / 6 - 3.5rem);">
                            <div class="h-full transition-all duration-500 
                                @if($stepNum < $currentStep) 
                                    bg-gradient-to-r from-approval-green to-approval-green/80 
                                @elseif($stepNum == $currentStep) 
                                    bg-gradient-to-r from-[#1a425f] to-gray-300 
                                @else 
                                    bg-gray-300 
                                @endif">
                            </div>
                        </div>
                    @endif
                </div>
                
                <!-- Step Label -->
                <div class="mt-4 text-center">
                    <p class=" font-semibold transition-colors duration-300
                        @if($stepNum < $currentStep) 
                            text-approval-green
                        @elseif($stepNum == $currentStep) 
                            text-[#1a425f]
                        @else 
                            text-gray-400
                        @endif">
                        {{ $stepInfo['name'] }}
                    </p>
                    @if($stepNum == $currentStep)
                        <p class="text-xs text-gray-500 mt-1">In Progress...</p>
                    @elseif($stepNum < $currentStep)
                        <p class="text-xs text-approval-green/70 mt-1">Completed</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
