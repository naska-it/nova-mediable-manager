<template>
    <div class="item-toolbar" :class="position">
        <a @click.prevent="$emit('toggle-sidebar', item)" class="toolbar-icon">
            <icon :type="'edit'" />
        </a>
        <a :href="item.url" target="_blank" class="toolbar-icon">
            <icon :type="'view'" :width="22" :height="18" :viewBox="'0 0 22 15'" />
        </a>
        <confirm-button v-on:handle-confirmed="$emit('delete-media', item.id)"  :sure-class="''" :mclass="'toolbar-icon is-danger'">
            <icon :type="'delete'" />
            <template #message>
                Are you sure you want to delete this media?
            </template>
        </confirm-button>
    </div>
</template>
<script>
    import ConfirmButton from '../partials/vue-confirmation-button';
    export default {
        props: ['item', 'position'],
        components: {
            ConfirmButton
        }
    }
</script>
