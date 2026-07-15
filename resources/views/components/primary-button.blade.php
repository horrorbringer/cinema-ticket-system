<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-gradient-to-r from-[#f12711] to-[#f5af19] border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:from-[#d42d02] hover:to-[#e09e00] focus:outline-none focus:ring-2 focus:ring-[#f5af19] focus:ring-offset-2 focus:ring-offset-[#0a0a0a] transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
