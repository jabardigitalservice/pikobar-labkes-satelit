<template>
  <div>
    <div class="form-check" v-for="(item, idx) in optionInstansiPengirim" :key="idx">
      <input class="form-check-input" type="radio" name="reg_fasyankes_pengirim" :value="item.key" v-model="input">
      <label class="form-check-label" for="fasyanrs">{{ item.value }}</label>
    </div>
  </div>
</template>

<script>
  import {
    optionInstansiPengirim
  } from '~/assets/js/constant/enum'
  export default {
    name: 'InputOptionInstansiPengirim',
    props: {
      form: {
        type: Object,
        default: {}
      },
      field: {
        type: String,
        default: ''
      },
    },
    data() {
      const input = this.form[this.field]
      return {
        input,
        optionInstansiPengirim
      }
    },
    watch: {
      'input'(newval) {
        if (newval) {
          this.form[this.field] = newval
        } else {
          this.form[this.field] = ''
        }
      },
    }
  }
</script>