<template>
    <!-- <transition name="slide" mode="out-in"> -->
        <div class="manager-sidebar relative" v-if="details">
            <span @click="$emit('toggle-sidebar', false)" class="cursor-pointer flex align-center absolute rounded-full text-white bg-primary">
                <icon :type="'x-circle'" :width="36" :height="36" :viewBox="'0 0 24 24'" />
            </span>
            <div class="p-2 bg-white max-w-sm rounded overflow-hidden shadow-lg" v-if="details">
                <img v-if="details.mime_type.startsWith('image')" class="w-full" :src="details.url" :alt="details.name" />
                <div v-else class="w-full h-48 flex items-center justify-center">
                    <icon :type="'m-document'" :width="48" :height="48" class="fill-no-stroke-current" />
                    <span class="">{{details.mime_type}}</span>
                </div>
                <div class=" px-6 py-4">
                    <div class="font-bold text-xl mb-2">{{details.name}}</div>
                    <p class="text-gray-700 text-base">
                        <ul class="rounded-lg mt-2 mb-3">
                            <li class="mb-1 truncate"><span class="font-bold">ID: </span><span>{{details.id}}</span></li>
                            <li class="mb-1 truncate"><span class="font-bold">Mime Type: </span><span>{{details.mime_type}}</span></li>
                            <li class="mb-1 truncate"><span class="font-bold">Url: </span><span>{{details.url}}</span></li>
                            <li class="mb-1 truncate"><span class="font-bold">Size: </span><span>{{bytesToSize(details.size)}}</span></li>
                        </ul>
                    </p>
                </div>
                <!-- <div class="px-6 py-4">
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#photography</span>
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700 mr-2">#travel</span>
                    <span class="inline-block bg-gray-200 rounded-full px-3 py-1 text-sm font-semibold text-gray-700">#winter</span>
                </div> -->
            </div>
        </div>
    <!-- </transition> -->
</template>
<script>
    import Mixin from '../../_mixin';
    export default{
        props:['details'],
        mixins: [Mixin],
        data() {
            return {
                //
            }
        },
        mounted() {
            //
        },
    }
</script>
