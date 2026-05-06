@props(['carId'])

<div id="delete-car-modal-{{ $carId }}" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm">
    <div class="bg-[#1e1e1e] border border-white/10 rounded-2xl p-6 w-80 shadow-2xl text-center" style="font-family: 'Montserrat', sans-serif;">
        <div class="w-12 h-12 bg-red-600/10 border border-red-600/30 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fa-solid fa-trash text-red-500 text-lg"></i>
        </div>
        <h3 class="text-white font-black uppercase tracking-tight text-sm mb-1">Delete Post</h3>
        <p class="text-gray-400 text-xs mb-6">Are you sure you want to delete this post?</p>
        <div class="flex gap-3">
            <button onclick="document.getElementById('delete-car-modal-{{ $carId }}').classList.add('hidden')"
                class="flex-1 border border-white/10 text-gray-400 hover:text-white hover:border-white/30 text-xs font-bold py-2.5 rounded-full transition-all duration-200 uppercase tracking-widest">
                No
            </button>
            <form action="/garage/delete/{{ $carId }}" method="POST" class="flex-1">
                @csrf
                @method('DELETE')
                <button type="submit"
                    class="w-full bg-red-600 hover:bg-red-500 text-white text-xs font-bold py-2.5 rounded-full transition-all duration-200 uppercase tracking-widest">
                    Yes, Delete
                </button>
            </form>
        </div>
    </div>
</div>