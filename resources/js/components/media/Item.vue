<template>
    <div class="media-item-wrapper">
        <img v-if="item.mime_type.startsWith('image')" :class="'h-' + height" class="object-cover w-full" :src="item.conversions.preview" :alt="item.name" />
        <div v-else :class="'h-' + height" class="w-full flex items-center justify-center">
            <icon :type="'m-document'" :width="48" :height="48" class="fill-no-stroke-current" />
            <span class="">{{item.mime_type}}</span>
        </div>
        <slot></slot>
    </div>
</template>
<script>
    export default{
        props:['item', 'height']
    }
</script>
