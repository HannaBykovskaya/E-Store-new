<div>
@if(auth()->check() && !auth()->user()->is_admin)
<div style="background-color: #fef2c0 !important; color: #b45309 !important;"
class="p-4 rounded-md shadow-md mb-6">
<strong>Внимание!</strong> Админпанель находится в тестовом режиме. Вы не можете редактировать содержимое.
</div>
@endif
</div>
