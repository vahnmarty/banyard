<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
        month: @js($getMonth()),
        year: @js($getYear()),
        allMonths: @js($getAllDays()) || [],

        init() {
            if (!this.currentMonth) {
                this.month = this.allMonths?.[0]?.month ?? 1;
            }
        },

        get currentMonth() {
            return this.allMonths.find(m => m.month === this.month) ?? {
                month: this.month,
                month_name: '',
                days: []
            };
        },

        nextMonth() {
            if (this.month === 12) {
                this.month = 1;
                this.year++;
            } else {
                this.month++;
            }
        },

        prevMonth() {
            if (this.month === 1) {
                this.month = 12;
                this.year--;
            } else {
                this.month--;
            }
        }
    }"
    x-init="init()"
        class="space-y-4"
    >

        <!-- HEADER -->
        <div class="flex items-center justify-between text-gray-900">

            <!-- PREV -->
            <button
                type="button"
                @click="prevMonth()"
                class="-m-1.5 flex items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
            >
                <span class="sr-only">Previous month</span>
                <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
                    <path d="M11.78 5.22a.75.75 0 0 1 0 1.06L8.06 10l3.72 3.72a.75.75 0 1 1-1.06 1.06l-4.25-4.25a.75.75 0 0 1 0-1.06l4.25-4.25a.75.75 0 0 1 1.06 0Z"/>
                </svg>
            </button>

            <!-- MONTH TITLE -->
            <div class="text-sm font-semibold">
                <span x-text="currentMonth.month_name"></span>
            </div>

            <!-- NEXT -->
            <button
                type="button"
                @click="nextMonth()"
                class="-m-1.5 flex items-center justify-center p-1.5 text-gray-400 hover:text-gray-500"
            >
                <span class="sr-only">Next month</span>
                <svg viewBox="0 0 20 20" fill="currentColor" class="size-5">
                    <path d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z"/>
                </svg>
            </button>

        </div>

        <!-- WEEK LABELS -->
        <div class="grid grid-cols-7 text-xs text-gray-500">
            <div>S</div>
            <div>M</div>
            <div>T</div>
            <div>W</div>
            <div>T</div>
            <div>F</div>
            <div>S</div>
        </div>

        <!-- DAYS GRID -->
        <div class="grid grid-cols-7 gap-px bg-gray-200 text-sm shadow-sm ring-1 ring-gray-200">

            <template x-for="(day, index) in currentMonth.days" :key="index">
                <div class="h-10 flex items-center justify-center bg-white">

                    <!-- EMPTY CELL -->
                    <template x-if="day === null">
                        <span></span>
                    </template>

                    <!-- DAY BUTTON -->
                    <template x-if="day !== null">
                        <button
                            type="button"
                            class="w-full h-full hover:bg-gray-100"
                            x-text="day"
                        ></button>
                    </template>

                </div>
            </template>

        </div>

    </div>

</x-dynamic-component>
