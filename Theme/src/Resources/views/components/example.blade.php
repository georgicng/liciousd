<!-- default product listing -->
<x-licious::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index')"
    :navigation-link="route('shop.home.index')"
/>

<!-- category product listing -->
<x-licious::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['category_id' => 1])"
    :navigation-link="route('shop.home.index')"
/>

<!-- featured product listing -->
<x-licious::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['featured' => 1])"
    :navigation-link="route('shop.home.index')"
/>

<!-- new product listing -->
<x-licious::products.carousel
    title="Men's Collections"
    :src="route('shop.api.products.index', ['new' => 1])"
    :navigation-link="route('shop.home.index')"
/>

<!-- basic/traditional form  -->
<x-licious::form action="">

    <!-- Type E-mail -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.label>
            Email
        </x-licious::form.control-group.label>

        <x-licious::form.control-group.control
            type="email"
            name="email"
            rules="required|email"
            value=""
            label="Email"
            placeholder="email@example.com"
        />

        <x-licious::form.control-group.error control-name="email" />
    </x-licious::form.control-group>

    <!-- Type Date -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.label>
            Date of Birth
        </x-licious::form.control-group.label>

        <x-licious::form.control-group.control
            type="date"
            id="dob"
            name="date_of_birth"
            value=""
            label="Date of Birth"
            placeholder="Date of Birth"
        />

        <x-licious::form.control-group.error control-name="date_of_birth" />
    </x-licious::form.control-group>

    <!-- Type Date Time -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.label>
            Start Timing
        </x-licious::form.control-group.label>

        <x-licious::form.control-group.control
            type="datetime"
            id="starts_from"
            name="starts_from"
            value=""
            label="Start Timing"
            placeholder="Start Timing"
        />

        <x-licious::form.control-group.error control-name="starts_from" />
    </x-licious::form.control-group>

    <!-- Type Text -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.label class="required">
            @lang('name')
        </x-licious::form.control-group.label>

        <x-licious::form.control-group.control
            type="text"
            name="name"
            rules="required"
            :value=""
            label="name"
            placeholder="name"
        />

        <x-licious::form.control-group.error control-name="name" />
    </x-licious::form.control-group>

    <!-- Type Select -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.label>
            @lang('licious::app.catalog.families.create.column')
        </x-licious::form.control-group.label>

        <x-licious::form.control-group.control
            type="select"
            name="column"
            rules="required"
            :label="trans('shop::app.catalog.families.create.column')"
        >
            <!-- Default Option -->
            <option value="">
                @lang('licious::app.catalog.families.create.select-group')
            </option>

            <option value="1">
                @lang('licious::app.catalog.families.create.main-column')
            </option>

            <option value="2">
                @lang('licious::app.catalog.families.create.right-column')
            </option>
        </x-licious::form.control-group.control>

        <x-licious::form.control-group.error control-name="column" />
    </x-licious::form.control-group>

    <!--Type Checkbox -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.control
            type="checkbox"
            id="is_unique"
            name="is_unique"
            value="1"
            for="is_unique"
        />

        <x-licious::form.control-group.label for="is_unique">
            @lang('licious::app.catalog.attributes.edit.is-unique')
        </x-licious::form.control-group.label>
    </x-licious::form.control-group>

    <!--Type Radio -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.control
            type="radio"
            id="is_unique"
            name="is_unique"
            value="1"
            for="is_unique"
        />

        <x-licious::form.control-group.label for="is_unique" />
            @lang('licious::app.catalog.attributes.edit.is-unique')
        </x-licious::form.control-group.label>
    </x-licious::form.control-group>

    <!-- Type Tinymce -->
    <x-licious::form.control-group>
        <x-licious::form.control-group.label>
            Description
        </x-licious::form.control-group.label>

        <x-licious::form.control-group.control
            type="textarea"
            class="description"
            name="description"
            rules="required"
            :value=""
            label="Description"
            :tinymce="true"
        />

        <x-licious::form.control-group.error control-name="description" />
    </x-licious::form.control-group>
</x-licious::form>

<!-- customized/ajax form -->
<x-licious::form
    v-slot="{ meta, errors, handleSubmit }"
    as="div"
>
    <form @submit="handleSubmit($event, callMethodInComponent)">
        <x-licious::form.control-group>
            <x-licious::form.control-group.label>
                Email
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="email"
                name="email"
                rules="required"
                :value="old('email')"
                label="Email"
                placeholder="email@example.com"
            />

            <x-licious::form.control-group.error control-name="email" />
        </x-licious::form.control-group>

        <button>Submit</button>
    </form>
</x-licious::form>

<!-- Shimmer -->
<x-licious::shimmer.checkout.onepage.payment-method />

<!-- tabs -->
<x-licious::tabs>
    <x-licious::tabs.item
        title="Tab 1"
    >
        Tab 1 Content
    </x-licious::tabs.item>

    <x-licious::tabs.item
        title="Tab 2"
    >
        Tab 2 Content
    </x-licious::tabs.item>
</x-licious::tabs>

<!-- accordion -->
<x-licious::accordion>
    <x-slot:header>
        Accordion Header
    </x-slot>

    <x-slot:content>
        Accordion Content
    </x-slot>
</x-licious::accordion>

<!-- modal -->
<x-licious::modal>
    <x-slot:toggle>
        Modal Toggle
    </x-slot>

    <x-slot:header>
        Modal Header
    </x-slot>

    <x-slot:content>
        Modal Content
    </x-slot>
</x-licious::modal>

<!-- drawer -->
<x-licious::drawer>
    <x-slot:toggle>
        Drawer Toggle
    </x-slot>

    <x-slot:header>
        Drawer Header
    </x-slot>

    <x-slot:content>
        Drawer Content
    </x-slot>
</x-licious::drawer>

<!-- dropdown -->
<x-licious::dropdown>
    <x-slot:toggle>
        Toogle
    </x-slot>

    <x-slot:content>
        Content
    </x-slot>
</x-licious::dropdown>

<!--Range Slider -->
<x-licious::range-slider
    ::key="refreshKey"
    default-type="price"
    ::default-allowed-max-range="allowedMaxPrice"
    ::default-min-range="minRange"
    ::default-max-range="maxRange"
    @change-range="setPriceRange($event)"
/>

<!-- Image/Media -->
<x-licious::media.images.lazy
    class="min-w-[250px] relative after:content-[' '] after:block after:pb-[calc(100%+9px)] bg-[#F5F5F5] group-hover:scale-105 transition-all duration-300"
    ::src="product.base_image.medium_image_url"
    ::key="product.id"
    ::index="product.id"
    width="291"
    height="300"
    ::alt="product.name"
/>

<!-- Page Title -->
<x-slot:title>
    @lang('Title')
</x-slot>

<!-- Page Layout -->
<x-licious::layouts>
   Page Content
</x-licious::layouts>

<!-- label class -->

<div class="label-canceled"></div>

<div class="label-info"></div>

<div class="label-completed"></div>

<div class="label-closed"></div>

<div class="label-processing"></div>

<div class="label-pending"></div>

<div class="label-canceled"></div>
