<template>
  <div>
    <select class="form-control" v-model="input"
      :class="{ 'is-invalid': form.errors && form.errors.has(field) && input != 'lainnya'}">
      <option value="" v-if="hasSemua">Semua</option>
      <template v-if="!is_object">
        <option :value="item" v-for="(item, $index) in options" :key="$index">{{ item }}</option>
      </template>
      <template v-if="is_object">
        <option :value="item.id" v-for="(item, $index) in options" :key="$index">{{ item.name }}</option>
      </template>
      <option value="lainnya" v-if="hasLainnya">Lainnya</option>
    </select>
    <input
      v-show="input == 'lainnya'"
      v-if="hasLainnya"
      class="form-control m-t"
      type="text"
      v-model="form[field]"
      :placeholder="placeholder"
      :class="{ 'is-invalid': form.errors && form.errors.has(field) }"
    />
    <has-error :form="form" :field="field" v-if="form.errors"/>
  </div>
</template>

<script>
export default {
  name: 'DynamicInput',
  data() {
    var input = this.form[this.field]
    if (this.options.find((option) => option.id === this.form[this.field]) === undefined && this.form[this.field] != '') {
      input = 'lainnya'
    }
    return {
      input: input,
      is_object: this.options[0] ? (typeof this.options[0] == 'object') : false,
    }
  },
  props: ['form', 'field', 'options', 'placeholder', 'hasLainnya', 'hasSemua'],
  methods: {
    reset() {
      this.input = this.form[this.field]
    },
  },
  watch: {
    'input': function(newval, oldval) {
      if (newval != 'lainnya') {
        this.form[this.field] = newval
      } else {
        this.form[this.field] = ''
      }
    },
  },
};
</script>

<style>
</style>