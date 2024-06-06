<div v-if="selectedMethod == 'bank_transfer'" class="mt-[20px]">
    <pre
        class="mb-[15px] text-[20px] max-sm:text-[16px]"
    >
        @{{ payment }}
        {{-- \Webkul\Payment\Payment::getAdditionalDetails($payment['method']) --}}
    </pre>
</div>
