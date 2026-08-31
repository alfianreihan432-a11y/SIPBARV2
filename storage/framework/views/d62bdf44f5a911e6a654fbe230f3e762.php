<?php # [BlazeFolded]:{flux::heading}:{C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/heading.blade.php}:{1781785518} ?>
<?php # [BlazeFolded]:{flux::subheading}:{C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/subheading.blade.php}:{1781785518} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1781785518} ?>
<?php # [BlazeFolded]:{flux::modal.close}:{C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/modal/close.blade.php}:{1781785518} ?>
<?php # [BlazeFolded]:{flux::button}:{C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/button/index.blade.php}:{1781785518} ?>
<?php
use App\Concerns\PasswordValidationRules;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
?>

<?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/modal/index.blade.php', $__blaze->compiledPath.'/508ee00e5f8286ca4fbad8fd7817f2a9.php'); ?>
<?php if (isset($__slots508ee00e5f8286ca4fbad8fd7817f2a9)) { $__slotsStack508ee00e5f8286ca4fbad8fd7817f2a9[] = $__slots508ee00e5f8286ca4fbad8fd7817f2a9; } ?>
<?php if (isset($__attrs508ee00e5f8286ca4fbad8fd7817f2a9)) { $__attrsStack508ee00e5f8286ca4fbad8fd7817f2a9[] = $__attrs508ee00e5f8286ca4fbad8fd7817f2a9; } ?>
<?php $__attrs508ee00e5f8286ca4fbad8fd7817f2a9 = ['name' => 'confirm-user-deletion','show' => $errors->isNotEmpty(),'focusable' => true,'class' => 'max-w-lg']; ?>
<?php $__slots508ee00e5f8286ca4fbad8fd7817f2a9 = []; ?>
<?php $__blaze->pushData($__attrs508ee00e5f8286ca4fbad8fd7817f2a9); ?>
<?php ob_start(); ?>
    <form method="POST" wire:submit="deleteUser" class="space-y-6">
        <div>
            <?php ob_start(); ?><div class="font-medium [:where(&amp;)]:text-zinc-800 [:where(&amp;)]:dark:text-white text-base [&amp;:has(+[data-flux-subheading])]:mb-2 [[data-flux-subheading]+&amp;]:mt-2" data-flux-heading><?php ob_start(); ?><?php echo e(__('Are you sure you want to delete your account?')); ?><?php echo trim(ob_get_clean()); ?></div>
<?php echo ltrim(ob_get_clean()); ?>

            <?php ob_start(); ?><div class="text-sm [:where(&amp;)]:text-zinc-500 [:where(&amp;)]:dark:text-white/70" data-flux-subheading>
    <?php ob_start(); ?>
                <?php echo e(__('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')); ?>

            <?php echo trim(ob_get_clean()); ?>

</div>
<?php echo ltrim(ob_get_clean()); ?>
        </div>

        <?php $__blaze->ensureRequired('C:\Users\ASUS\SIPBARV2\vendor\livewire\flux\src/../stubs/resources/views/flux/input/index.blade.php', $__blaze->compiledPath.'/55b794e22b19c20ac72e6cdb2272cf02.php'); ?>
<?php $__blaze->pushData(['wire:model' => 'password','label' => __('Password'),'type' => 'password','viewable' => true]); ?>
<?php _55b794e22b19c20ac72e6cdb2272cf02($__blaze, ['wire:model' => 'password','label' => __('Password'),'type' => 'password','viewable' => true], [], ['label', 'viewable'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php $__blaze->popData(); ?>

        <div class="flex justify-end space-x-2 rtl:space-x-reverse">
            <?php ob_start(); ?><ui-close data-flux-modal-close >
    <?php ob_start(); ?>
                <?php ob_start(); ?><button type="button" class="relative items-center font-medium justify-center gap-2 whitespace-nowrap disabled:opacity-75 dark:disabled:opacity-75 disabled:cursor-default disabled:pointer-events-none justify-center h-10 text-sm rounded-lg ps-4 pe-4 inline-flex  bg-zinc-800/5 hover:bg-zinc-800/10 dark:bg-white/10 dark:hover:bg-white/20 text-zinc-800 dark:text-white   [[data-flux-button-group]_&amp;]:border-e [:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-0 [[data-flux-button-group]_&amp;]:border-zinc-200/80 dark:[[data-flux-button-group]_&amp;]:border-zinc-900/50" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target">
        <?php ob_start(); ?><?php echo e(__('Cancel')); ?><?php echo trim(ob_get_clean()); ?>

    </button>
<?php echo ltrim(ob_get_clean()); ?>
            <?php echo trim(ob_get_clean()); ?>

</ui-close>
<?php echo ltrim(ob_get_clean()); ?>

            <?php ob_start(); ?><button type="submit" class="relative items-center font-medium justify-center gap-2 whitespace-nowrap disabled:opacity-75 dark:disabled:opacity-75 disabled:cursor-default disabled:pointer-events-none justify-center h-10 text-sm rounded-lg ps-4 pe-4 inline-flex  bg-red-500 hover:bg-red-600 dark:bg-red-600 dark:hover:bg-red-500 text-white  shadow-[inset_0px_1px_var(--color-red-500),inset_0px_2px_--theme(--color-white/.15)] dark:shadow-none [[data-flux-button-group]_&amp;]:border-e [:is([data-flux-button-group]&gt;&amp;:last-child,_[data-flux-button-group]_:last-child&gt;&amp;)]:border-e-0 [[data-flux-button-group]_&amp;]:border-red-600 dark:[[data-flux-button-group]_&amp;]:border-red-900/25 *:transition-opacity [&amp;[disabled]&gt;:not([data-flux-loading-indicator])]:opacity-0 [&amp;[disabled]&gt;[data-flux-loading-indicator]]:opacity-100 [&amp;[disabled]]:pointer-events-none" data-flux-button="data-flux-button" data-flux-group-target="data-flux-group-target" data-test="confirm-delete-user-button">
        <div class="absolute inset-0 flex items-center justify-center opacity-0" data-flux-loading-indicator>
                <svg class="shrink-0 [:where(&amp;)]:size-4 animate-spin" data-flux-icon xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" aria-hidden="true" data-slot="icon">
    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
</svg>
                    </div>
        
        
                    
            
            <span><?php ob_start(); ?>
                <?php echo e(__('Delete account')); ?>

            <?php echo trim(ob_get_clean()); ?></span>
    </button>
<?php echo ltrim(ob_get_clean()); ?>
        </div>
    </form>
<?php $__slots508ee00e5f8286ca4fbad8fd7817f2a9['slot'] = new \Illuminate\View\ComponentSlot(trim(ob_get_clean()), []); ?>
<?php $__blaze->pushSlots($__slots508ee00e5f8286ca4fbad8fd7817f2a9); ?>
<?php _508ee00e5f8286ca4fbad8fd7817f2a9($__blaze, $__attrs508ee00e5f8286ca4fbad8fd7817f2a9, $__slots508ee00e5f8286ca4fbad8fd7817f2a9, ['show', 'focusable'], [], $__this ?? (isset($this) ? $this : null)); ?>
<?php if (! empty($__slotsStack508ee00e5f8286ca4fbad8fd7817f2a9)) { $__slots508ee00e5f8286ca4fbad8fd7817f2a9 = array_pop($__slotsStack508ee00e5f8286ca4fbad8fd7817f2a9); } ?>
<?php if (! empty($__attrsStack508ee00e5f8286ca4fbad8fd7817f2a9)) { $__attrs508ee00e5f8286ca4fbad8fd7817f2a9 = array_pop($__attrsStack508ee00e5f8286ca4fbad8fd7817f2a9); } ?>
<?php $__blaze->popData(); ?><?php /**PATH C:\Users\ASUS\SIPBARV2\storage\framework\views/livewire/views/68492654.blade.php ENDPATH**/ ?>