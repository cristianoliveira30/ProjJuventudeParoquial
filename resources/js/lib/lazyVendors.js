let flowbitePromise;

export function ensureFlowbite() {
    flowbitePromise ??= import("flowbite");

    return flowbitePromise;
}
