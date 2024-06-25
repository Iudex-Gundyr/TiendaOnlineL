<div class="container mt-4">
    @if (session('success'))
        <div class="custom-alert custom-alert-success">
            <span class="custom-alert-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>Éxito:</strong> {{ session('success') }}  
        </div>
    @endif

    @if (session('error'))
        <div class="custom-alert custom-alert-error">
            <span class="custom-alert-closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
            <strong>Error:</strong> {{ session('error') }}  
        </div>
    @endif
</div>