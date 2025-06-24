<script setup>
import { nextTick, ref, onMounted } from 'vue'
import { useI18n } from 'vue-i18n'
import { useSipuni } from './composables.js'
import { useRoute, useRouter } from 'vue-router'
import { usePage } from '@/composables/page.js'
import lodash from 'lodash'
import objectByKeys from '@/utils/objectByKeys.js'
import Filter from '@/components/filter/index.vue'
import FilterForm from './forms/Filter.vue'
import $http from '@/http'

const prefix = localStorage.getItem('prefix')

const TableBoardRef = ref()

const $route = useRoute()
const $router = useRouter()

const { t: $t } = useI18n()

const { $_columns, $_filter, $_filterKeys, $_relations, $_show } = useSipuni()

const $_usePage = usePage()
const { $_items, $_loading, $_total, $_from, $_rows, $_resource, $_page, dTable, $_record } = $_usePage
const { $_fetchItems, $_setParams, $_updatePage, $_setRecord, $_hook } = $_usePage

$_resource.value = 'credit/sipuni-call'

$_filter.value = {
    ...$_filter.value,
    ...lodash.pick($route.query, Object.keys($_filter.value)),
}

$_setParams({
    with: [...$_relations.value],
    page: $_page.value,
    show: $_show.value,
    ...$_filter.value
})

nextTick(() => {
    $_fetchItems()
})

function setFilter() {
    $_setParams({
        ...$_filter.value,
        page: 1
    })

    nextTick(() => {
        $_fetchItems()
    })
}

function clearFilter() {
    $_filter.value = objectByKeys($_filterKeys, null)
    setFilter()
}

onMounted(() => {
    dTable.value = TableBoardRef.value.dTable // required for linking with a table
})

$_hook.setData = (data) => {
    return data.map((item) => {
        item['play_in_process'] = false
        return item
    })
}

let rowDataForm = null
const audioRecord = ref()

async function toPlayRecord() {
    try {
        dTable.value.$_addRowClass(`#${rowDataForm.DT_RowId}`, 'loading-content')

        const res = await $http['clientCredit'].get(`${prefix}/credit/sipuni-call/${$_record.value?.id}/record`, {
            responseType: "blob",
        })

        dTable.value.$_removeRowClass(`#${rowDataForm.DT_RowId}`, 'loading-content')

        const url = window.URL.createObjectURL(res.data)
        audioRecord.value = new Audio(url)

        audioRecord.value.play()
        rowDataForm.play_in_process = true

        audioRecord.value.addEventListener('ended', function (e) {
            rowDataForm.play_in_process = false

        })
    } catch (e) {
        audioRecord.value = null
        rowDataForm.play_in_process = false
        dTable.value.$_removeRowClass(`#${rowDataForm.DT_RowId}`, 'loading-content')
        throw e
    }

}

function toPauseRecord() {
    audioRecord.value.pause()
    rowDataForm.play_in_process = false
}

function handlerTableActions(event, type, id) {
    if (type === 'play') {
        if (!rowDataForm || rowDataForm.id !== id) {
            if (audioRecord.value) {
                audioRecord.value.pause()
                audioRecord.value = null
            }

            $_setRecord(id)

            if (rowDataForm) {
                rowDataForm.play_in_process = false
                //console.log(rowDataForm)
                if ($_items.value.some(r => r.DT_RowId === rowDataForm.DT_RowId)) {
                    dTable.value.$_removeRowClass(`#${rowDataForm.DT_RowId}`, 'loading-content')
                }
            }

            rowDataForm = dTable.value.$_getRowData(event)

            toPlayRecord()
        } else {
            rowDataForm.play_in_process = true
            audioRecord.value.play()
        }
    }

    if (type === 'pause') {
        toPauseRecord()
    }
}

</script>
<template>
    <c-pageTitle :title="$t('menu.sipuni')" />

    <Card :pt="{ content: { class: 'p-0' } }">
        <template #content>
            <div class="mb-3">
                <Filter @submit.prevent="setFilter" :loading="$_loading" @setClear="clearFilter" v-slot="{ isOpened }">
                    <div class="grid lg:grid-cols-4 gap-2 w-full">
                        <FilterForm v-model="$_filter" :is-opened="isOpened"></FilterForm>
                    </div>
                </Filter>
            </div>

            <c-tableBoard ref="TableBoardRef" :items="$_items" :columns="$_columns" :total="$_total"
                :rows="$_rows" :loading="$_loading" v-model:from="$_from" @page="$_updatePage" @actionEvent="handlerTableActions">
            </c-tableBoard>
        </template>
    </Card>
</template>
