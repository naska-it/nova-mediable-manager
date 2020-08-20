<template>
    <default-field :field="field" :errors="errors">
        <template slot="field">
            <div class="laravel-mediable-manager">
                <div v-if="mediableMedia.length" class="gallery flex flex-wrap m-auto -mx-2 p-2">
                    <div class="w-1/5 px-2 mb-4" v-for="item in mediableMedia" :key="item.id" :title="item.name">
                        <media-item :item="item" :height="36">

                            <mediable-toolbar v-on:update-mediable="updateMediable" :item="item" :mediable_media="mediableMedia" :mediable="mediable" :position="'right'" />

                        </media-item>
                    </div>
                </div>
                <button v-if="allowManager" @click.prevent="openManager = !openManager" class="btn btn-default btn-primary">Media Manager</button>
            </div>
        </template>
        <div v-if="openManager">
            <modal @modal-close="handleClose">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden mx-auto">
                    <medaible-manager :init-mediable="mediable" :init-mediable-media="mediableMedia" v-on:update-mediable-field="updateMediable" />
                </div>
            </modal>
        </div>
    </default-field>
</template>
<script>
import { FormField, HandlesValidationErrors } from 'laravel-nova';
import MedaibleManager from '../components/Tool';
import MediaItem from '../components/media/Item';
import MediableToolbar from '../components/media/MediableToolbar';

export default {
    mixins: [FormField, HandlesValidationErrors],

    props: ['resourceName', 'resourceId', 'field'],

    data() {
        return {
            config: Nova.config.novaMediableManager,
            mediable: this.field.mediable,
            mediableMedia: this.field.media,
            openManager: false
        };
    },
    components: {
        MedaibleManager,
        MediaItem,
        MediableToolbar
    },

    mounted() {
        console.log(this.field.attribute);
    },

    methods: {
        /*
         * Set the initial, internal value for the field.
         */
        setInitialValue() {
            this.value = this.field.value || '';
        },

        fill(formData) {
            formData.append(
                this.field.attribute,
                this.value ? JSON.stringify(_.map(this.mediableMedia, 'id')) : "[]"
            );
        },

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
            return this.config.fields.form.allow_manager;
        }
    }
}

</script>
