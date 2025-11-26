# Bootstrap Modal Backdrop Fix - Madrasah Index

## Completed Tasks
- [x] Remove the duplicate shown.bs.modal listener (the commented one for aria-hidden) in edit modal
- [x] In the onHidden function for edit modal, add manual removal of modal-open class and modal-backdrop elements
- [x] Remove the manual removal of Leaflet elements in onHidden for edit modal, as map.remove() should suffice
- [x] Apply similar fixes to the add modal (remove manual Leaflet element removal and add modal backdrop cleanup)

## Summary
Fixed Bootstrap modal backdrop lingering issue by:
1. Removing duplicate event listeners that could cause conflicts
2. Adding proper cleanup of modal-open class and backdrop elements in hidden handlers
3. Relying on map.remove() for Leaflet cleanup instead of manual DOM manipulation

The modal should now close properly without leaving backdrop or modal-open class behind.
