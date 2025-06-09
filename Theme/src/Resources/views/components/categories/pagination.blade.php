<v-pagination {{ $attributes }}></v-pagination>

@pushOnce('scripts')
<script
    type="text/x-template"
    id="v-pagination-template"
>
    <nav aria-label="..." class="cr-pagination mt-[24px] flex justify-center w-full">
        <ul class="pagination flex text-[1rem] border-[1px] border-solid border-[#eee] rounded-[10px]">
            <li 
                class="page-item px-[0.75rem] py-[0.375rem] bg-[#f7f7f8] rounded-l-[10px] border-r-[1px] border-solid border-[#eee] hover:bg-[#f7f7f8]" 
                :class="{ 'disabled cursor-not-allowed': meta.current_page == 1 }"
            >
                <span v-if=" meta.current_page == 1" class="page-link">Previous</span>
                <button v-else type="button" class="page-link" @click="$emit('goto', meta.current_page - 1)">Previous</button>
            </li>
            <li 
                v-for="index in meta.last_page" 
                class="page-item px-[0.75rem] py-[0.375rem] border-r-[1px] border-solid border-[#eee] hover:bg-[#f7f7f8]"
                :class="{ 'active disabled cursor-not-allowed': meta.current_page == index }" 
                aria-current="page">
                <span v-if="meta.current_page == index" class="page-link">@{{index}}</span>
                <button v-else type="button" class="page-link" @click="$emit('goto', index)">@{{index}}</button>
            </li>            
            <li 
                class="page-item px-[0.75rem] py-[0.375rem] rounded-r-[10px] hover:bg-[#f7f7f8]"
                :class="{ 'disabled cursor-not-allowed': meta.current_page == meta.last_page }"
            >
                <span v-if="meta.current_page == meta.last_page" class="page-link">Next</span>
                <button v-else type="button" class="page-link" @click="$emit('goto', meta.current_page + 1)">Next</button>
            </li>
        </ul>
    </nav>
 </script>

    <script type="module">
        app.component('v-pagination', {
            template: '#v-pagination-template',
            props: ['loading', 'meta'],
        });
    </script>
@endPushOnce
