<?php

use Phonyland\Fake\Fake;

test('fake classes can be called by an alias', function () {
    expect(🙃()->address)->toBeInstanceOf(Fake::class);
    expect(🙃()->📫)->toBeInstanceOf(Fake::class);
    expect(🙃()->alphabet)->toBeInstanceOf(Fake::class);
    expect(🙃()->🔤)->toBeInstanceOf(Fake::class);
    expect(🙃()->ancient)->toBeInstanceOf(Fake::class);
    expect(🙃()->📜)->toBeInstanceOf(Fake::class);
    expect(🙃()->person)->toBeInstanceOf(Fake::class);
    expect(🙃()->coin)->toBeInstanceOf(Fake::class);
    expect(🙃()->currency)->toBeInstanceOf(Fake::class);
});
