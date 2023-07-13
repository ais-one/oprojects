import FabricTool, { ConfigureCanvasProps } from "./fabrictool"

class SelectTool extends FabricTool {
  configureCanvas(args: ConfigureCanvasProps): () => void {
    let canvas = this._canvas
    canvas.isDrawingMode = false
    canvas.selection = true
    canvas.forEachObject((o) => (o.selectable = o.evented = true))

    const lockObject = (obj: fabric.Object, lock: boolean, ops: String) => {
      if (!obj) return obj
      console.log(ops, obj)

      const index = canvas.getObjects().indexOf(obj)
      // console.log('obj index', index)

      if (lock) {
        obj.setControlVisible('mtr', false) // remove rotating point
        obj.set('fill', args.selectFillColor)
      } else {
        obj.setControlVisible('mtr', true) // put back rotating point
        obj.set('fill', 'rgba(255, 255, 255, 0.1)')
      }
      // obj.cornerSize = 0
      // obj.hasBorders = false
      // obj.borderColor = args.borderColor || '#ffff00'
      // obj.selectionBackgroundColor = args.selectionBackgroundColor || 'rgba(255, 0, 0, 0.3)'

      obj.lockMovementX = lock
      obj.lockMovementY = lock
      obj.lockRotation = lock
      obj.lockScalingX = lock
      obj.lockScalingY = lock
      obj.lockScalingFlip = lock
      obj.lockSkewingX = lock
      obj.lockSkewingY = lock
      return obj
    }
    
    const handleSelect = (e: any) => {
      console.log('e', e)
      if (e.deselected.length) {
        e.deselected[0].set('fill', 'rgba(255, 255, 255, 0.0)')
      }
      const aa : any = canvas.getActiveObject()
      lockObject(aa, true, 'SINGLE SELECT')
    }

    const handleCleared = (e: any) => {
      const aa : any = canvas.getActiveObject()
      lockObject(aa, false, 'CLEARED')
    }

    const handleCreate = (e: any) => {
      const aa : any = canvas.getActiveObject()
      lockObject(aa, true, 'CREATED')
    }

    canvas.on("selection:created", handleCreate)
    canvas.on("selection:updated", handleSelect)
    canvas.on("before:selection:cleared", handleCleared)
    return () => {
      canvas.off("selection:created", handleCreate)
      canvas.off("selection:updated", handleSelect)
      canvas.off("before:selection:cleared", handleCleared)
    }
  }
}

export default SelectTool
