<div  v-if="cart?.payment_method == 'bank_transfer'" class="mt-[20px]">
    <pre
        class="mb-[15px] text-[20px] max-sm:text-[16px]"
    >
        JSON.parse(@json(\Webkul\Payment\Payment::getAdditionalDetails('bank_transfer')))?.value
    </pre>
</div>
