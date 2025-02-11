<x-licious::layouts.account>
    <!-- Page Title -->
    <x-slot:title>
        @lang('licious::app.customers.account.profile.edit-profile')
    </x-slot>

    <!-- Breadcrumbs -->
    @section('breadcrumbs')
        <x-licious::breadcrumbs name="profile.edit" />
    @endSection

    <h2 class="mb-8 text-2xl font-medium">
        @lang('licious::app.customers.account.profile.edit-profile')
    </h2>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.before', ['customer' => $customer]) !!}

    <!-- Profile Edit Form -->
    <x-licious::form
        :action="route('shop.customers.account.profile.update')"
        enctype="multipart/form-data"
    >
        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.before', ['customer' => $customer]) !!}

        <x-licious::form.control-group class="mt-4">
            <x-licious::form.control-group.control
                type="image"
                class="!p-0 rounded-xl text-gray-700 mb-0"
                name="image[]"
                :label="trans('Image')"
                :is-multiple="false"
                accepted-types="image/*"
                :src="$customer->image_url"
            />

            <x-licious::form.control-group.error control-name="image[]" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.image.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label class="required">
                @lang('licious::app.customers.account.profile.first-name')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="text"
                name="first_name"
                rules="required"
                :value="old('first_name') ?? $customer->first_name"
                :label="trans('shop::app.customers.account.profile.first-name')"
                :placeholder="trans('shop::app.customers.account.profile.first-name')"
            />

            <x-licious::form.control-group.error control-name="first_name" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.first_name.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label class="required">
                @lang('licious::app.customers.account.profile.last-name')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="text"
                name="last_name"
                rules="required"
                :value="old('last_name') ?? $customer->last_name"
                :label="trans('shop::app.customers.account.profile.last-name')"
                :placeholder="trans('shop::app.customers.account.profile.last-name')"
            />

            <x-licious::form.control-group.error control-name="last_name" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.last_name.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label class="required">
                @lang('licious::app.customers.account.profile.email')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="text"
                name="email"
                rules="required|email"
                :value="old('email') ?? $customer->email"
                :label="trans('shop::app.customers.account.profile.email')"
                :placeholder="trans('shop::app.customers.account.profile.email')"
            />

            <x-licious::form.control-group.error control-name="email" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.email.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label class="required">
                @lang('licious::app.customers.account.profile.phone')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="text"
                name="phone"
                rules="required|phone"
                :value="old('phone') ?? $customer->phone"
                :label="trans('shop::app.customers.account.profile.phone')"
                :placeholder="trans('shop::app.customers.account.profile.phone')"
            />

            <x-licious::form.control-group.error control-name="phone" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.phone.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label class="required">
                @lang('licious::app.customers.account.profile.gender')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="select"
                class="mb-3"
                name="gender"
                rules="required"
                :value="old('gender') ?? $customer->gender"
                :aria-label="trans('shop::app.customers.account.profile.select-gender')"
                :label="trans('shop::app.customers.account.profile.gender')"
            >
                <option value="Other">
                    @lang('licious::app.customers.account.profile.other')
                </option>

                <option value="Male">
                    @lang('licious::app.customers.account.profile.male')
                </option>

                <option value="Female">
                    @lang('licious::app.customers.account.profile.female')
                </option>
            </x-licious::form.control-group.control>

            <x-licious::form.control-group.error control-name="gender" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.gender.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label>
                @lang('licious::app.customers.account.profile.dob')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="date"
                name="date_of_birth"
                :value="old('date_of_birth') ?? $customer->date_of_birth"
                :label="trans('shop::app.customers.account.profile.dob')"
                :placeholder="trans('shop::app.customers.account.profile.dob')"
            />

            <x-licious::form.control-group.error control-name="date_of_birth" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.date_of_birth.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label>
                @lang('licious::app.customers.account.profile.current-password')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="password"
                name="current_password"
                value=""
                :label="trans('shop::app.customers.account.profile.current-password')"
                :placeholder="trans('shop::app.customers.account.profile.current-password')"
            />

            <x-licious::form.control-group.error control-name="current_password" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.old_password.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label>
                @lang('licious::app.customers.account.profile.new-password')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="password"
                name="new_password"
                value=""
                :label="trans('shop::app.customers.account.profile.new-password')"
                :placeholder="trans('shop::app.customers.account.profile.new-password')"
            />

            <x-licious::form.control-group.error control-name="new_password" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password.after') !!}

        <x-licious::form.control-group>
            <x-licious::form.control-group.label>
                @lang('licious::app.customers.account.profile.confirm-password')
            </x-licious::form.control-group.label>

            <x-licious::form.control-group.control
                type="password"
                name="new_password_confirmation"
                rules="confirmed:@new_password"
                value=""
                :label="trans('shop::app.customers.account.profile.confirm-password')"
                :placeholder="trans('shop::app.customers.account.profile.confirm-password')"
            />

            <x-licious::form.control-group.error control-name="new_password_confirmation" />
        </x-licious::form.control-group>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.new_password_confirmation.after') !!}

        <div class="select-none items-center flex gap-1.5 mb-4">
            <input
                type="checkbox"
                name="subscribed_to_news_letter"
                id="is-subscribed"
                class="hidden peer"
                @checked($customer->subscribed_to_news_letter)
            />

            <label
                class="icon-uncheck text-2xl text-navyBlue peer-checked:icon-check-box peer-checked:text-navyBlue cursor-pointer"
                for="is-subscribed"
            ></label>

            <label
                class="text-base text-[#6E6E6E] max-sm:text-xs ltr:pl-0 rtl:pr-0 select-none cursor-pointer"
                for="is-subscribed"
            >
                @lang('licious::app.customers.account.profile.subscribe-to-newsletter')
            </label>
        </div>

        <button
            type="submit"
            class="primary-button block m-0 w-max py-3 px-11 rounded-2xl text-base text-center"
        >
            @lang('licious::app.customers.account.profile.save')
        </button>

        {!! view_render_event('bagisto.shop.customers.account.profile.edit_form_controls.after', ['customer' => $customer]) !!}

    </x-licious::form>

    {!! view_render_event('bagisto.shop.customers.account.profile.edit.after', ['customer' => $customer]) !!}

</x-licious::layouts.account>
