@props(['title', 'description'])

<div class="flex flex-col w-full gap-1 mb-6">
    <flux:heading size="xl" class="font-serif! text-3xl! font-medium!">{{ $title }}</flux:heading>
    <flux:subheading class="text-sm! uppercase tracking-widest opacity-60">{{ $description }}</flux:subheading>
</div>
