<template>
    <div class="laravel-mediable-manager">
        <div style="width: 6rem">
            <media-item v-if="mediableMedia.length" :item="mediableMedia[0]" :height="12" >
                <div v-if="allowManager" class="item-toolbar center">
                    <a @click.prevent="openManager = !openManager" class="toolbar-icon">
                        <icon :type="'edit'" />
                    </a>
                </div>
            </media-item>
            <button v-else-if="allowManager" @click.prevent="openManager = !openManager"  class="btn btn-icon">
                <icon :type="'edit'" />
            </button>
        </div>
        <div v-if="openManager">
            <modal @modal-close="handleClose">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mx-auto">
                    <medaible-manager :init-mediable="mediable" :init-mediable-media="mediableMedia" v-on:update-mediable-field="updateMediable" />
                </div>
            </modal>
        </div>
    </div>
</template>

<script>
import MedaibleManager from '../components/Tool';
import MediaItem from '../components/media/Item';
import MediableToolbar from '../components/media/MediableToolbar';
export default {
    props: ['resourceName', 'field'],
    data() {
        return {
            config: Nova.config.novaMediableManager,
            mediable: this.field.mediable,
            mediableMedia: this.field.media,
            openManager: false
        };
    },
    components: {
        MediaItem,
        MedaibleManager,
        MediableToolbar
    },

    mounted() {
        //
    },

    methods: {
        handleClose() {
            this.openManager = false;
        },
        updateMediable(media) {
            if (media) {
                this.mediableMedia = media;
            } else {
                Nova.request()({
                    url: '/nova-vendor/nova-mediable-manager/mediable',
                    method: 'post',
                    data: this.mediable,
                }).then(r => {
                    this.mediableMedia = r.data;
                }).catch(e => {
                    console.log(e);
                });
            }
            
        }
    },

    computed: {
        allowManager() {
            return this.config.fields.index.allow_manager;
        }
    }
}
</script>
