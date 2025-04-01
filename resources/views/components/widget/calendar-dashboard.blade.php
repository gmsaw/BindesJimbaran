<div x-data="calendarApp()" x-init="init()" class="mx-auto w-[90%] max-w-[400px] min-w-[320px] max-h-[450px] bg-white rounded-xl shadow-lg p-4 mt-3">
    <header class="flex items-center justify-between mb-4">
      <h3 x-text="`${months[month]} ${year}`" class="font-semibold text-lg"></h3>
      <nav class="flex items-center gap-2">
        <button @click="prevMonth()" id="prev" class="w-5 h-5 relative border-none bg-transparent cursor-pointer">
          <span class="absolute top-1/2 left-1/2 w-2.5 h-2.5 border-t-2 border-r-2 border-gray-400 transform -translate-x-1/2 -translate-y-1/2 rotate-[225deg]"></span>
        </button>
        <button @click="nextMonth()" id="next" class="w-5 h-5 relative border-none bg-transparent cursor-pointer">
          <span class="absolute top-1/2 left-1/2 w-2.5 h-2.5 border-t-2 border-r-2 border-gray-400 transform -translate-x-1/2 -translate-y-1/2 rotate-45"></span>
        </button>
      </nav>
    </header>
    
    <section>
      <ul class="flex flex-wrap font-semibold text-center">
        <template x-for="day in ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']" :key="day">
          <li class="w-[calc(100%/7)] aspect-square flex items-center justify-center" x-text="day"></li>
        </template>
      </ul>
      
      <ul class="flex flex-wrap text-center">
        <template x-for="(date, index) in calendarDates" :key="index">
          <li 
            @click="selectDate(date)"
            class="w-[calc(100%/7)] aspect-square flex items-center justify-center relative"
            :class="{
              'text-gray-400': !date.isCurrentMonth,
              'text-white bg-indigo-500': date.isToday,
              'cursor-pointer hover:text-indigo-500': date.isCurrentMonth
            }"
          >
            <span x-text="date.day"></span>
            <span 
              x-show="date.isToday" 
              class="absolute top-1/2 left-1/2 w-8 h-8 bg-black rounded-full transform -translate-x-1/2 -translate-y-1/2 -z-10"
            ></span>
          </li>
        </template>
      </ul>
    </section>
  </div>

  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('calendarApp', () => ({
        months: [
          'January', 'February', 'March', 'April', 'May', 'June', 
          'July', 'August', 'September', 'October', 'November', 'December'
        ],
        date: new Date(),
        month: new Date().getMonth(),
        year: new Date().getFullYear(),
        calendarDates: [],
        
        init() {
          this.renderCalendar();
        },
        
        renderCalendar() {
          const startDay = new Date(this.year, this.month, 1).getDay();
          const endDate = new Date(this.year, this.month + 1, 0).getDate();
          const endDay = new Date(this.year, this.month, endDate).getDay();
          const endDatePrev = new Date(this.year, this.month, 0).getDate();
          
          let dates = [];
          
          // Previous month days
          for (let i = startDay; i > 0; i--) {
            dates.push({
              day: endDatePrev - i + 1,
              isCurrentMonth: false,
              isToday: false,
              date: new Date(this.year, this.month - 1, endDatePrev - i + 1)
            });
          }
          
          // Current month days
          for (let i = 1; i <= endDate; i++) {
            const isToday = 
              i === this.date.getDate() && 
              this.month === new Date().getMonth() && 
              this.year === new Date().getFullYear();
            
            dates.push({
              day: i,
              isCurrentMonth: true,
              isToday: isToday,
              date: new Date(this.year, this.month, i)
            });
          }
          
          // Next month days
          for (let i = endDay; i < 6; i++) {
            dates.push({
              day: i - endDay + 1,
              isCurrentMonth: false,
              isToday: false,
              date: new Date(this.year, this.month + 1, i - endDay + 1)
            });
          }
          
          this.calendarDates = dates;
        },
        
        prevMonth() {
          if (this.month === 0) {
            this.year--;
            this.month = 11;
          } else {
            this.month--;
          }
          this.date = new Date(this.year, this.month, this.date.getDate());
          this.renderCalendar();
        },
        
        nextMonth() {
          if (this.month === 11) {
            this.year++;
            this.month = 0;
          } else {
            this.month++;
          }
          this.date = new Date(this.year, this.month, this.date.getDate());
          this.renderCalendar();
        },
        
        selectDate(date) {
          if (date.isCurrentMonth) {
            this.date = date.date;
            this.renderCalendar();
            // You can add additional logic here when a date is selected
          }
        }
      }));
    });
  </script>