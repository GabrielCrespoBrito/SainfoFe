import React from 'react';

export default function CsrfFieldtoken() {
  const cstf = document.querySelector("[name=csrf-token]").content
  return (
    <input type="hidden" name="_token" value={cstf}  />
  );
}