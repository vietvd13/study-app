export function isAvailable(parent, child) {
  try {
    const childArray = String(child).split('.');
    let evaluted = parent;
    childArray.forEach((x) => {
      evaluted = evaluted[x];
    });
    return !!evaluted;
  } catch {
    return false;
  }
}
