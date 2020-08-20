<template>
    <modal @modal-close="handleClose">
        <form
            @submit.prevent="handleConfirm"
            slot-scope="props"
            class="bg-white rounded-lg shadow-lg overflow-hidden"
            style="width: 460px"
        >
            <slot>
                <div class="p-8">
                    <heading  class="mb-6">{{ __('General Modal') }}</heading>
                    <p class="text-80 leading-normal">{{__('General modal contents')}}</p>
                </div>
            </slot>

            <div class="bg-30 px-6 py-3 flex">
                <div class="ml-auto">
                    <button type="button" data-testid="cancel-button" dusk="cancel-general-button" @click.prevent="handleClose" class="btn text-80 font-normal h-9 px-3 mr-3 btn-link">{{__('Cancel')}}</button>
                    <button id="confirm-delete-button" ref="confirmButton" data-testid="confirm-button" type="submit" class="btn btn-default btn-danger">Delete</button>
                </div>
            </div>
        </form>
    </modal>
</template>

<script>
    export default {
        //props: ['size'],
        name: "GeneralModal",
        methods: {
            handleClose() {
                this.$emit('close')
            },
            handleConfirm() {
                this.$emit('confirm')
            },
        },
        /**
         * Mount the component.
         */
        mounted() {
            this.$refs.confirmButton.focus()
        },
    }
</script>

<style scoped>
</style>
