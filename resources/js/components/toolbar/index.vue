<template>
    <div class="flex items-center relative shadow h-header bg-white z-20 px-view mb-4">
        <div class="relative z-50 w-full max-w-xs">
            <div class="relative">
                <div class="relative">
                    <icon class="fill-current absolute search-icon-center ml-3 text-80" :type="'search'" :width="20" :height="20" :viewBox="'0 0 20 20'"/>
                    <input v-model="filter.name" type="search" placeholder="Search" class="pl-search w-full form-global-search">
                </div>
            </div>
        </div>
        <div class="v-popover ml-auto h-9 flex items-center dropdown-right">
            <!-- <date-time-picker
              :dusk="'item-from'"
              class="w-full form-control form-input form-input-bordered ml-2"
              :name="'item-from'"
              :value="filter.from"
              :placeholder="'From'"
              @change="handleFrom"
            />
            <date-time-picker
              :dusk="'item-to'"
              class="w-full form-control form-input form-input-bordered ml-2"
              :name="'item-to'"
              :value="filter.to"
              :placeholder="'To'"
              @change="handleTo"
            /> -->
            <select v-model="filter.per_page" class="shadow-md block border-0 cursor-pointer form-control form-select ml-2">
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="20">20</option>
                <option value="50">50</option>
            </select>
            <select v-model="filter.type" class="shadow-md block border-0 cursor-pointer form-control form-select ml-2">
                <option :value="null">All</option>
                <option v-for="opt in options.mime" :value="opt.value">{{opt.text}}</option>
            </select>
            <button @click.prevent="getMedia()" class="btn btn-default btn-primary ml-2">Filter</button>
            <button class="btn btn-default btn-danger whitespace-no-wrap ml-2">{{bytesToSize(stats)}}</button>
            <button @click.prevent="showUpload()" class="btn btn-default btn-outline ml-2">
                <span class="whitespace-no-wrap" v-if="!uploaderOpen">
                    <icon :type="'m-upload'"/>
                </span>
                <span class="whitespace-no-wrap" v-else >Close Uploader</span>
            </button>
        </div>
    </div>
</template>
<script>
    import Mixin from '../../_mixin';
    export default {
        mixins: [Mixin],
        props: ['filter','stats','uploaderOpen'],
        data() {
            return {
                options: {
                    mime: [
                        { value: 'image', text: 'Images' },
                        { value: 'application/pdf', text: 'Pdf' },
                    ]
                }
            }
        },
        methods: {
            handleFrom(value) {
                this.filter.from = value;
            },
            handleTo(value) {
                this.filter.to = value;
            },
            getMedia() {
                this.$emit('get-media');
            },
            showUpload() {
                this.$emit('show-uploader');
            }
        }
    }
</script>
