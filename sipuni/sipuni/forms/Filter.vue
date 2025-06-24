<template>
    <div>
        <InputText v-model="value['filter[credit_id]']" :placeholder="field('credit_id')" class="w-full" />
    </div>
    <div>
        <Autocomplete v-model="value['filter[creator_id]']"
            :resource="`${VITE_APP_URL_BASE}/common/public/records/user_employee`" inputId="integeronly" dropdown
            :placeholder="field('creator_id')"
            :inputProps="{
                autocomplete: 'off'
            }"
            class="w-full" />
    </div>
    <div>
        <c-calendarInput v-model="value['filter[created_at][0]']" :placeholder="`${field('created_at')} от`"
            inputClass="w-full" class="w-full" />
    </div>
    <div>
        <c-calendarInput v-model="value['filter[created_at][1]']" :placeholder="`${field('created_at')} до`"
            inputClass="w-full" class="w-full" />
    </div>
</template>
<script setup>
import { defineProps, defineEmits, computed } from 'vue'
import UseSystemStore from '@/store/common/system'
import Autocomplete from '@/components/autocomplete/index.vue'

const $_systemStore = UseSystemStore()
const { field, enums } = $_systemStore

const VITE_APP_URL_BASE = import.meta.env.VITE_APP_URL_BASE


const TransactionPaymentType = Object.entries(enums.Company.TransactionPaymentType).map(([val, name]) => {
    return {
        name: name,
        value: val
    }
})

const TransactionType = Object.entries(enums.Company.TransactionType).map(([val, name]) => {
    return {
        name: name,
        value: val
    }
})

const TransactionStatus = Object.entries(enums.Company.TransactionStatus).map(([val, name]) => {
    return {
        name: name,
        value: val
    }
})

// TODO: no dependency for this type
// eslint-disable-next-line no-unused-vars
const TransactionReasonType = Object.entries(enums.Company.TransactionReasonType).map(([val, name]) => {
    return {
        name: name,
        value: val
    }
})


const $emit = defineEmits(['update:modelValue'])

const $props = defineProps({
    modelValue: {
        type: Object
    },
    isOpened: {
        type: Boolean
    }
})

const value = computed({
    get() {
        return $props.modelValue
    },
    set(val) {
        $emit('update:modelValue', val)
    }
})
</script>
