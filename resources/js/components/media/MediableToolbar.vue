<template>
    <div class="item-toolbar" :class="position">
        <confirm-button v-if="attachedItem(item.id)" v-on:handle-confirmed="detach(item)"  :sure-class="''" :mclass="'toolbar-icon is-danger'">
            <span class="action-description">Detach Item</span>
            <icon :type="'x-circle'" :width="22" :height="22" :viewBox="'0 0 22 22'"  />
            <template #message>
                Are you sure you want to detach this media?
            </template>
        </confirm-button>
        <confirm-button v-else v-on:handle-confirmed="attach(item)"  :sure-class="''" :mclass="'toolbar-icon'">
            <span class="action-description">Attach Item</span>
            <icon :type="'add'" :viewBox="'0 0 24 24'"/>
            <template #message>
                Are you sure you want to attach this media?
            </template>
        </confirm-button>
    </div>
</template>
<script>
    import ConfirmButton from '../partials/vue-confirmation-button';
    export default {
        props: ['item', 'position', 'mediable', 'mediable_media'],
        components: {
            ConfirmButton
        },
        data() {
            return {
                //
            };
        },
        methods: {

            attachedItem(id) {
                return (_.indexOf(_.map(this.mediable_media, 'id'), id) !== -1);
            },

            attach(item) {
                if (!this.mediable.model_id) {
                    this.toggleMediable(item, 'attach');
                } else {
                    this.handleMediable(item, 'attach');
                }
            },
            detach(item) {
                if (!this.mediable.model_id) {
                    this.toggleMediable(item, 'detach');
                } else {
                    this.handleMediable(item, 'detach');

                }

            },
            toggleMediable(item, action) {
                
                let media;
                
                if (this.mediable.is_single && action == 'attach') {
                    media = [item];
                } else {
                    media = _.xorBy(this.mediable_media, [item], 'id');                   
                }

                this.$emit('update-mediable', media);
                
            },

            handleMediable(item, action) {
                Nova.request()({
                    url: '/nova-vendor/nova-mediable-manager/'+action+'/' + item.id,
                    method: 'post',
                    data: this.mediable,
                }).then(r => {
                    this.$emit('update-mediable', null);
                }).catch(e => {
                    console.log(e);
                });
            }
        }
    }
</script>
