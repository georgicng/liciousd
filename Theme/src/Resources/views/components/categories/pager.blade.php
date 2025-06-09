<v-pager {{ $attributes }}></v-pager>

@pushOnce('scripts')
<script
    type="text/x-template"
    id="v-pager-template"
>
    <div>
        {!! view_render_event('bagisto.shop.categories.view.load_more_button.before') !!}

        <!-- Load More Button -->
        <button
            class="secondary-button block mx-auto w-max py-3 mt-14 px-11 rounded-2xl text-base text-center"
            @click="$emit('load-more')"
            v-if="! loading"
        >
            @lang('licious::app.categories.view.load-more')
        </button>

        <button
            v-else
            class="secondary-button block w-max mx-auto py-3.5 mt-14 px-[74.5px] rounded-2xl text-base text-center"
        >
            <!-- Spinner -->
            <img
                class="animate-spin h-5 w-5 text-navyBlue"
                src="{{ bagisto_asset('images/spinner.svg') }}"
                alt="Loading"
            />
        </button>

        {!! view_render_event('bagisto.shop.categories.view.grid.load_more_button.after') !!}
    </div>
 </script>

    <script type="module">
        app.component('v-pager', {
            template: '#v-pager-template',
            props: ['loading'],
        });
    </script>
@endPushOnce