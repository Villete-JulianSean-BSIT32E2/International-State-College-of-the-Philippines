<!-- My Account Modal -->
<div id="myAccountModal" class="fixed inset-0 flex items-center rounded-xl justify-center bg-black bg-opacity-50 z-50 hidden">
  <div class="bg-white w-full max-w-md rounded-xl shadow-lg">
    
  <div class="h-24 w-full bg-gradient-to-r from-blue-600 via-blue-500 to-indigo-600 rounded-xl"></div>
  <div class="top-16 z-10 flex items-center flex-col gap-4 px-5 py-5">
    <div class="-mt-20">
      <svg xmlns="http://www.w3.org/2000/svg" class="w-24 h-24 text-gray-300 bg-white rounded-full p-2 border-4 border-white shadow" fill="currentColor" viewBox="0 0 24 24">
        <path d="M12 12c2.7 0 4.8-2.1 4.8-4.8S14.7 2.4 12 2.4 7.2 4.5 7.2 7.2 9.3 12 12 12zm0 2.4c-3.2 0-9.6 1.6-9.6 4.8V21h19.2v-1.8c0-3.2-6.4-4.8-9.6-4.8z"/>
      </svg>
    </div>

    <div class="space-y-4 text-sm text-gray-700">
      <div>
        <span class="font-semibold">Student Name:</span>
        <span>Juan Dela Cruz</span>
      </div>
      <div>
        <span class="font-semibold">RFID:</span>
        <span>1234567890</span>
      </div>
      <div>
        <span class="font-semibold">Fullname:</span>
        <span>Juan Antonio Dela Cruz</span>
      </div>
      <div>
        <span class="font-semibold">Section:</span>
        <span>BSIT-3B</span>
      </div>
      <div>
        <span class="font-semibold">Year:</span>
        <span>3rd Year</span>
      </div>
      <div>
        <span class="font-semibold">Semester Enrolled:</span>
        <span>2nd Semester</span>
      </div>
    </div>

    <div class="flex items-center gap-3">
      <button onclick="closeMyAccount()"
        class="bg-red-400 transition-all gradient text-[15px] text-white px-3 py-[6px] rounded-full flex items-center gap-1"
      >
        Close
      </button>
    </div>
  </div>

    
  </div>
</div>
