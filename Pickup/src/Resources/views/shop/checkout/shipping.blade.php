<div v-if="cart?.selected_shipping_rate_method?.includes('Pickup Centre')" class="mt-[20px]">
    <pre
        class="mb-[15px] text-[20px] max-sm:text-[16px]"
    >
       JSON.parse(@json(getPickupLocations()))[cart.selected_shipping_rate_method]
    </pre>
</div>
