@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-white/20 bg-white/5 text-white placeholder-white/30 focus:border-[#f5af19] focus:ring-[#f5af19] rounded-md shadow-sm']) }}>
