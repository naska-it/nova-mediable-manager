<template>
    <div class="confirmation-wrapper">
        <button v-show="!confirm" :class="mclass" @click.prevent="confirm=!confirm">
                        <slot></slot>
        </button>
        <div v-show="confirm" class='bg-white text-black p-2 text-center rounded' :class="sureClass">
                <slot name="message">Are you sure ?</slot>
        </div>
        <div v-show="confirm" class="flex align-center justify-center">
          <button @click.prevent="handleConfirmed()" class="btn btn-sm btn-danger rounded-l px-2">
            Yes
          </button>
          <button @click.prevent="confirm=!confirm" class="btn btn-sm btn-primary rounded-r px-2">
            No
          </button>
        </div>
    </div>
</template>

<script>
  export default {
    name: 'vue-confirmation-button',
    props: {
        mclass: String,
        sureClass: String
    },
    data() {
      return {
        confirm: false
      }
    },
    methods: {
        handleConfirmed() {
            this.$emit('handle-confirmed');
            this.confirm = false
        }
    }
  }
</script>
