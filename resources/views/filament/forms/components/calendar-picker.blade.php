<x-dynamic-component
    :component="$getFieldWrapperView()"
    :field="$field"
>
    <div
        x-data="{
            state: $wire.{{ $applyStateBindingModifiers("\$entangle('{$getStatePath()}')") }},
            month: @js($getMonth()),
            year: @js($getYear()),
            min_date: @js($getMinDate()),
            max_date: @js($getMaxDate()),
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
            },

            isDisabled(day) {
                if (!day) return true;

                const date = new Date(this.year, this.month - 1, day);

                const [minY, minM, minD] = this.min_date.split('-').map(Number);
                const [maxY, maxM, maxD] = this.max_date.split('-').map(Number);

                const min = new Date(minY, minM - 1, minD);
                const max = new Date(maxY, maxM - 1, maxD);

                return date < min || date > max;
            },

            selectDate(day) {
                if (!day) return;

                if (this.isDisabled(day)) return;

                this.state = `${this.year}-${String(this.month).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
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
                <div
                    class="h-10 flex items-center justify-center"
                    :class="{
                        'bg-white': !isDisabled(day),
                        'bg-gray-50': isDisabled(day)
                    }"
                >

                    <!-- EMPTY CELL -->
                    <template x-if="day === null">
                        <span></span>
                    </template>

                    <!-- DAY BUTTON -->
                    <template x-if="day !== null">
                        <button
                            type="button"
                            @click="selectDate(day)"
                            :disabled="isDisabled(day)"
                            class="w-full h-full"
                            :class="{
                                'hover:bg-gray-100 hover:text-primary-500': !isDisabled(day),
                                'text-gray-300 cursor-not-allowed': isDisabled(day),
                                'bg-primary-500 text-white': state === `${year}-${String(month).padStart(2,'0')}-${String(day).padStart(2,'0')}`
                            }"
                            x-text="day"
                        ></button>
                    </template>

                </div>
            </template>

        </div>

    </div>

</x-dynamic-component>
