<template>

    <loading-view :loading="initialLoading" class="laravel-mediable-manager" :class="{'uploader-open' : uploaderOpen}">

        <toolbar :filter="filter" :stats="stats" :uploader-open="uploaderOpen" v-on:get-media="getMedia" v-on:show-uploader="showUploader" />
        <div>
            <!-- Mediable EditMode: <span v-if="mediable.model_id">Edit</span><span v-else>Create</span> -->
            <div v-if="mediableMedia.length" class="gallery flex flex-wrap m-auto -mx-2 p-2">
                <div class="w-1/5 px-2 mb-4" v-for="item in mediableMedia" :key="item.id" :title="item.name">
                    <media-item :item="item" :height="48">
                            <mediable-toolbar v-on:update-mediable="updateMediable" :item="item" :mediable_media="mediableMedia" :mediable="mediable" :position="'right'" />
                    </media-item>
                </div>
            </div>
        </div>
        <loading-card :loading="loading">

            <uploader v-if="uploaderOpen" :accept="config.accept" />

            <div v-if="media.data.length" class="gallery flex flex-wrap m-auto -mx-2 p-2">
                <div class="w-1/5 px-2 mb-4" v-for="item in media.data" :key="item.id" :title="item.name">
                    <media-item :item="item" :height="48">

                        <media-toolbar v-on:delete-media="deleteMedia" v-on:toggle-sidebar="toggleSidebar" :item="item" :position="'left'" />

                        <mediable-toolbar v-if="mediable.model" v-on:update-mediable="updateMediable" :item="item" :mediable_media="mediableMedia" :mediable="mediable" :position="'right'" />

                    </media-item>
                </div>
            </div>
            <empty-media v-else v-on:show-uploader="showUploader" />

            <media-details :details="sidebar" v-on:toggle-sidebar="toggleSidebar"/>

            <pagination-links v-if="media.data.length" @page="getMedia" :page="media.meta.current_page" :pages="media.meta.last_page" @previous="selectPreviousPage" @next="selectNextPage">
            </pagination-links>

        </loading-card>

    </loading-view>

</template>
<script>
import { Paginatable, PerPageable } from 'laravel-nova';
import Uploader from './uploader';
import Toolbar from './toolbar';
import MediaItem from './media/Item';
import MediaToolbar from './media/ItemToolbar';
import MediableToolbar from './media/MediableToolbar';
import MediaDetails from './media/ItemDetails';
import EmptyMedia from './partials/Empty';
export default {
    mixins: [
        Paginatable,
        PerPageable
    ],

    components: {
        Uploader,
        Toolbar,
        MediaDetails,
        MediaItem,
        MediaToolbar,
        MediableToolbar,
        EmptyMedia
    },

    props: {
        initMediable: {
            type: Object,
            default: () => ({
                model: null,
                model_id: null,
                collection: null,
                is_single: false,
                is_flexible: false,
            }),
        },
        initMediableMedia: {
            type: Array,
            default: () => []
        }
    },

    data() {
        return {
            config: Nova.config.novaMediableManager,
            media: {
                data: [],
                links: {},
                meta: {}
            },
            mediableMedia: this.initMediableMedia,
            mediable: this.initMediable,
            filter: {
                name: null,
                type: null,
                from: null,
                to: null,
                per_page: 10,
            },
            stats: 0,
            sidebar: false,
            uploaderOpen: false,
            initialLoading: true,
            loading: false,
        }
    },

    mounted() {
        this.getMedia();
        this.getStats();
        if (this.mediable.model_id) {
            this.getMediable();
        }
    },

    methods: {
        getMedia(page = null) {
            this.loading = true;
            Nova.request()({
                url: '/nova-vendor/nova-mediable-manager/index',
                method: 'get',
                params: this.filterData(page),
            }).then(r => {
                this.media = r.data;
                this.filter.per_page = r.data.meta.per_page;
                this.initialLoading = false;
                this.loading = false;
                Nova.$emit('resources-loaded', true);
            }).catch(e => {
                Nova.$emit('resources-loaded', true);
            });
        },

        deleteMedia(id) {
            let url = '/nova-vendor/nova-mediable-manager/delete/' + id;
            Nova.request().get(url).then(r => {
                this.loading = false;
                this.getMedia(this.media.meta.current_page);
                this.$toasted.show('Item Deleted!', { type: 'success' })
            }).catch(e => {
                this.loading = false;
                console.log(e);
            });
        },

        getStats() {
            Nova.request()({
                url: '/nova-vendor/nova-mediable-manager/stats',
                method: 'get',
                //params: this.filterData(page),
            }).then(r => {
                this.stats = r.data;
            }).catch(e => {});
        },

        getMediable() {
            console.log('get mediable manager');
            Nova.request()({
                url: '/nova-vendor/nova-mediable-manager/mediable',
                method: 'post',
                data: this.mediable,
            }).then(r => {
                this.updateMediable(r.data);
            }).catch(e => {
                console.log(e);
            });
        },

        updateMediable(media) {
             if(media) {
                console.log('update mediable manager 1');
                this.mediableMedia = media;
                this.$emit('update-mediable-field', media);
             } else {
                console.log('update mediable manager 2');
                this.getMediable();
             }
             
        },

        toggleSidebar(item) {
            this.sidebar = item;
        },

        showUploader() {
            if (this.uploaderOpen) {
                this.getMedia();
            }       
            this.uploaderOpen = !this.uploaderOpen;
        },

        filterData: function(page) {
            let data = {};

            data.page = page;
            data.per_page = this.filter.per_page;

            if (this.filter.name) {
                data.name = this.filter.name;
            }
            if (this.filter.type) {
                data.mime_type = this.filter.type;
            }
            if (this.filter.from) {
                data.from = this.filter.from;
            }
            if (this.filter.to) {
                data.to = this.filter.to;
            }

            return data;
        }

    }
}

</script>
