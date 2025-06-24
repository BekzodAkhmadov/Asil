import { toRef, ref, getCurrentInstance } from 'vue'
import objectByKeys from '@/utils/objectByKeys.js'
import { useRoute } from 'vue-router'
import { useTableColumns } from '@/composables/tableColumns.js'
import UseSystemStore from '@/store/common/system'
import { useI18n } from 'vue-i18n'

export function useSipuni() {
    const $route = useRoute()
    const { $_collectColumns } = useTableColumns()
    const $_systemStore = UseSystemStore()
    const { field } = $_systemStore
    const { proxy } = getCurrentInstance()
    const { t: $t } = useI18n()

    const $_relations = ref(['creator.profile', 'company'])

    const $_filterKeys = [
        'filter[creator_id]',
        'filter[credit_id]',
        'filter[created_at][0]',
        'filter[created_at][1]'
    ]

    const $_filter = ref(objectByKeys($_filterKeys, null))

    const $_show = ref($route.query?.show || null)

    const columnInstance = [
        'index',
        'company',
        'call_id',
        'creator',
        'phone',
        'created_at',
        'actions'
    ]

    const columns = { 
        call_id: {
            th: {
                title: $t('common.call_id'),
                style: 'min-width: 220px'
            },
            className: 'col-name',
            data: 'call_id',
            render: function (data, type) {
                return data
            }
        },
        company: {
            th: {
                class: 'col-company col-priority-0',
                title: field('company'),
                style: 'min-width: 220px'
            },
            className: 'col-priority-0',
            data: 'company',
            render: function (data) {
                return `<div>${data.name}</div>
                        <div class="text-caption text-gray-500 !font-bold">
                            ${data.company_name}
                        </div>`
            }
        },
        creator: {
            th: {
                title: $t('common.creator'),
                style: 'min-width: 220px'
            },
            className: 'col-creator',
            data: 'creator',
            render: function (data, type) {
                return `<div>${data.username}</div>`
            }
        },

        phone: {
            th: {
                title: field('phone'),
                style: 'min-width: 220px'
            },
            className: 'col-creator',
            data: 'phone',
            render: function (data, type) {
                return proxy.$filters.phone(data)
            }
        },

        actions: {
            th: {
                class: 'col-actions',
            },
            className: 'col-actions col-fit',
            data: null,
            render: function (data) {
                const pauseBtn = `<a href="#"
                                        title='Pause'
                                        class="relative items-center inline-flex text-center align-bottom justify-center leading-[normal] p-1 rounded-full bg-transparent border border border-orange-300 hover:bg-orange-300 hover:text-white focus:outline-none focus:outline-offset-0 focus:ring focus:ring-primary-400/50 dark:focus:ring-primary-300/50 hover:bg-primary-300/20 transition duration-200 ease-in-out cursor-pointer overflow-hidden select-none"
                                        onclick="event.preventDefault(); event.stopClickAway = true; dtAction(event, 'pause', ${data.id})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M15 19q-.825 0-1.412-.587T13 17V7q0-.825.588-1.412T15 5h2q.825 0 1.413.588T19 7v10q0 .825-.587 1.413T17 19zm-8 0q-.825 0-1.412-.587T5 17V7q0-.825.588-1.412T7 5h2q.825 0 1.413.588T11 7v10q0 .825-.587 1.413T9 19zm8-2h2V7h-2zm-8 0h2V7H7zM7 7v10zm8 0v10z"/></svg>
                                    </a>`

                const playBtn = `<a href="#"
                                        title='Play'
                                        class="relative items-center inline-flex text-center align-bottom justify-center leading-[normal] p-1 rounded-full bg-transparent border border border-orange-300 hover:bg-orange-300 hover:text-white focus:outline-none focus:outline-offset-0 focus:ring focus:ring-primary-400/50 dark:focus:ring-primary-300/50 hover:bg-primary-300/20 transition duration-200 ease-in-out cursor-pointer overflow-hidden select-none"
                                        onclick="event.preventDefault(); event.stopClickAway = true; dtAction(event, 'play', ${data.id})">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"><path fill="currentColor" d="M6.25 8.047c0-1.93 2.093-3.132 3.76-2.16l6.777 3.954c1.653.964 1.653 3.354 0 4.319l-6.777 3.953c-1.667.972-3.76-.23-3.76-2.16zm3.004-.864a1 1 0 0 0-1.504.864v7.906a1 1 0 0 0 1.504.864l6.777-3.953a1 1 0 0 0 0-1.728z"/><path fill="currentColor" d="M7.75 8.047a1 1 0 0 1 1.504-.864l6.777 3.953a1 1 0 0 1 0 1.728l-6.777 3.953a1 1 0 0 1-1.504-.864z" opacity="0.5"/></svg>
                                    </a>`

                const inProcessIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16M8 9v6m12-5v4M4 10v4m12-7v10"/></svg>`

                return `<div class="flex items-center">
                            ${data.play_in_process ? pauseBtn : playBtn}
                        </div>`
            }
        }
    }

    const $_columns = $_collectColumns(columnInstance, columns)

    return {
        $_columns,
        $_filter: toRef($_filter),
        $_filterKeys,
        $_relations: toRef($_relations),
        $_show: toRef($_show)
    }
}
