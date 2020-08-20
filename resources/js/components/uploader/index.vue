<template>
    <div class="uploader shadow-lg">
        <table class="table w-full">
            <thead>
                <tr>
                    <td>#</td>
                    <td class="text-center">Thumb</td>
                    <td>Name</td>
                    <td>Size</td>
                    <td>Status</td>
                    <td>Action</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td colspan="6">
                        <div class="flex items-center justify-center">
                            <file-upload class="btn btn-default btn-primary" ref="upload" :accept="accept" v-model="files" :custom-action="customAction" :multiple='true' @input-file="inputFile" @input-filter="inputFilter">
                                <span v-if="!files.length">Browse Files</span>
                                <span v-else>Add More Files</span>
                            </file-upload>
                            <button class="btn btn-default bg-success text-white ml-2" v-show="!$refs.upload || !$refs.upload.active" @click.prevent="$refs.upload.active = true" type="button">Start upload</button>
                            <button class="btn btn-default bg-success text-white ml-2" v-show="$refs.upload && $refs.upload.active" @click.prevent="$refs.upload.active = false" type="button">Stop upload</button>
                            <button v-if="files.length" @click.prevent="files = []" class="btn btn-default btn-danger ml-2" type="button">Clear List</button>
                        </div>
                    </td>
                </tr>
                <tr v-for="(file, index) in files"> <!-- :class="{'item-uploaded': file.success}" -->
                    <td>{{index + 1}}</td>
                    <td>
                        <div class="thumb-wrapper">
                            <img v-if="file.thumb" :src="file.thumb" />
                            <span v-else>No Image</span>
                        </div>
                    </td>
                    <td>{{file.name}}</td>
                    <td>{{bytesToSize(file.size)}}</td>
                    <td v-if="file.error"><span class="status is-error">{{file.error}}</span></td>
                    <td v-else-if="file.success"><span class="status is-success">success</span></td>
                    <td v-else-if="file.active"><span class="status is-active">active</span></td>
                    <td v-else><span class="status is-ready">Ready</span></td>
                    <td>
                        <div class="inline-flex items-center">
                            <!-- cancel if started upload item -->
                            <a v-if="file.active" @click.prevent="$refs.upload.update(file, {active: false})" title="Cancel" class="action">
                                <icon /></a>
                            <!-- retry upload if error -->
                            <a v-else-if="file.error && file.error !== 'compressing' && $refs.upload.features.html5" @click.prevent="$refs.upload.update(file, {active: true, error: '', progress: '0.00'})" title="Retry" class="action">
                                <icon :type="'refresh'"/>
                            </a>
                            <!-- upload single file -->
                            <a v-else-if="!file.success" :class="{'text-30': true, disabled: file.success || file.error === 'compressing'}" @click.prevent="file.success || file.error === 'compressing' ? false : $refs.upload.update(file, {active: true})" title="Upload" class="action">
                                <icon :type="'play'" />
                            </a>
                            <!-- remove from upload list -->
                            <a v-if="!file.success || file.error" @click.prevent="$refs.upload.remove(file)" title="Remove" class="action is-danger">
                                <icon /></a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
import Mixin from '../../_mixin';
export default {
    mixins: [Mixin],
    props: ['accept'],
    data() {
        return {
            files: [],
        };
    },

    methods: {

        customAction: async function(file, component) {
            let formData = new FormData();
            formData.append("file", file.file);

            return await this.fileUpload(formData);

        },

        fileUpload: function(formData) {
            let url = '/nova-vendor/nova-mediable-manager/upload';
            Nova.request().post(url, formData).then(r => {
                this.$toasted.show('Item upload success', { type: 'success' })
            }).catch(e => {
                this.$toasted.show(e, { type: 'danger' });
            });
        },

        inputFile: function(newFile, oldFile) {

            /*if (newFile && oldFile) {

                if (!newFile.active && oldFile.active) {
                    // Get response data
                    console.log('response', newFile.response.status)
                    if (newFile.xhr) {
                        console.log('status', newFile.xhr.status)
                    }

                }

                if (newFile.error !== oldFile.error) {
                     console.log('error', newFile.error, newFile)
                   }
            }*/

            
        },

        inputFilter: function(newFile, oldFile, prevent) {
            if (newFile && !oldFile) {
                // Filter non-image file
                /*if (!/\.(jpeg|jpe|jpg|gif|png|webp)$/i.test(newFile.name)) {
                  return prevent()
                }*/
            }

            newFile.blob = ''
            let URL = window.URL || window.webkitURL
            if (URL && URL.createObjectURL) {
                newFile.blob = URL.createObjectURL(newFile.file)
            }
            newFile.thumb = ''
            if (newFile.blob && newFile.type.substr(0, 6) === 'image/') {
                newFile.thumb = newFile.blob
            }
        }
    }
}

</script>
