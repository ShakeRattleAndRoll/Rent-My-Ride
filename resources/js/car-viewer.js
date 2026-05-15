import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js'
import { DRACOLoader } from 'three/examples/jsm/loaders/DRACOLoader.js'
import { RGBELoader } from 'three/examples/jsm/loaders/RGBELoader.js'

document.addEventListener('livewire:navigated', initCarViewer)
document.addEventListener('DOMContentLoaded', initCarViewer)

function initCarViewer() {

    const container   = document.getElementById('car-viewer')
    const panControl  = document.getElementById('car-pan-control')
    const zoomControl = document.getElementById('car-zoom-control')
    const panVal      = document.getElementById('car-pan-val')
    const zoomVal     = document.getElementById('car-zoom-val')

    if (!container) return
    if (container.dataset.initialized) return
    container.dataset.initialized = true

    let disposed = false
    let isVisible = true
    let frameId = null

    let camera, scene, renderer, controls, carModel
    let currentAzimuth = 0
    let currentDistance = 6.2
    let targetAzimuth = 0
    let targetDistance = 6.2
    let lastFrameTime = performance.now()
    let lastUserInteraction = performance.now()

    const wheels = []

    const getSize = () => ({
        width:  Math.max(container.clientWidth,  1),
        height: Math.max(container.clientHeight, 1)
    })

    // SCENE
    scene = new THREE.Scene()
    scene.background = new THREE.Color(0xf8fafc)
    scene.fog = new THREE.Fog(0xf8fafc, 10, 28)

    const initialSize = getSize()

    // CAMERA
    camera = new THREE.PerspectiveCamera(40, initialSize.width / initialSize.height, 0.1, 100)
    camera.position.set(0, 1.45, targetDistance)

    // RENDERER
    renderer = new THREE.WebGLRenderer({
        antialias: window.devicePixelRatio <= 1.5,
        alpha: true,
        powerPreference: 'high-performance'
    })
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 1.5))
    renderer.setSize(initialSize.width, initialSize.height)
    renderer.toneMapping = THREE.ACESFilmicToneMapping
    renderer.toneMappingExposure = 0.85
    renderer.outputColorSpace = THREE.SRGBColorSpace
    container.appendChild(renderer.domElement)

    // CONTROLS
    controls = new OrbitControls(camera, renderer.domElement)
    controls.enabled = false
    controls.enableDamping = false
    controls.maxDistance = 9
    controls.minDistance = 3.8
    controls.maxPolarAngle = THREE.MathUtils.degToRad(90)
    controls.minPolarAngle = THREE.MathUtils.degToRad(62)
    controls.target.set(0, 0.5, 0)
    controls.autoRotate = false
    controls.enableZoom = false
    controls.enablePan = false

    // LIGHTING
    const ambientLight = new THREE.HemisphereLight(0xffffff, 0xdbeafe, 1.35)
    const keyLight = new THREE.DirectionalLight(0xffffff, 2.2)
    const rimLight = new THREE.PointLight(0xa3e635, 4.5, 8)
    keyLight.position.set(3, 5, -4)
    rimLight.position.set(-2.5, 1.8, 2.4)
    scene.add(ambientLight, keyLight, rimLight)

    function applyCameraTargets() {
        camera.position.set(
            Math.sin(currentAzimuth) * currentDistance,
            1.45,
            Math.cos(currentAzimuth) * currentDistance
        )
        camera.lookAt(0, 0.5, 0)
    }

    // SLIDER BINDING
    const bindSliderControls = () => {
        const markInteraction = () => { lastUserInteraction = performance.now() }

        const onPan = () => {
            targetAzimuth = THREE.MathUtils.degToRad(Number(panControl.value))
            if (panVal) panVal.textContent = Math.round(panControl.value) + '°'
            markInteraction()
        }

        const onZoom = () => {
            targetDistance = Number(zoomControl.value)
            if (zoomVal) zoomVal.textContent = parseFloat(zoomControl.value).toFixed(1) + '×'
            markInteraction()
        }

        panControl?.addEventListener('input', onPan)
        zoomControl?.addEventListener('input', onZoom)

        targetAzimuth  = THREE.MathUtils.degToRad(Number(panControl?.value  ?? 0))
        targetDistance = Number(zoomControl?.value ?? 6.2)
        applyCameraTargets()

        return () => {
            panControl?.removeEventListener('input', onPan)
            zoomControl?.removeEventListener('input', onZoom)
        }
    }

    const unbindSliderControls = bindSliderControls()

    // HDRI
    new RGBELoader().load('/hdri/autoshop_01_4k.hdr', (texture) => {
        if (disposed) { texture.dispose(); return }
        texture.mapping = THREE.EquirectangularReflectionMapping
        scene.environment = texture
    })

    // MATERIALS
    const bodyMaterial = new THREE.MeshPhysicalMaterial({
        color: 0xff0000, metalness: 1.0, roughness: 0.5, clearcoat: 1.0, clearcoatRoughness: 0.03
    })
    const detailsMaterial = new THREE.MeshStandardMaterial({
        color: 0xffffff, metalness: 1.0, roughness: 0.5
    })
    const glassMaterial = new THREE.MeshPhysicalMaterial({
        color: 0xffffff, metalness: 0.25, roughness: 0, transmission: 1.0
    })

    // GLTF
    const dracoLoader = new DRACOLoader()
    dracoLoader.setDecoderPath('https://www.gstatic.com/draco/v1/decoders/')
    const loader = new GLTFLoader()
    loader.setDRACOLoader(dracoLoader)
    loader.load('/models/ferrari.glb', (gltf) => {
        if (disposed) return
        carModel = gltf.scene.children[0]
        carModel.getObjectByName('body').material   = bodyMaterial
        carModel.getObjectByName('rim_fl').material = detailsMaterial
        carModel.getObjectByName('rim_fr').material = detailsMaterial
        carModel.getObjectByName('rim_rr').material = detailsMaterial
        carModel.getObjectByName('rim_rl').material = detailsMaterial
        carModel.getObjectByName('trim').material   = detailsMaterial
        carModel.getObjectByName('glass').material  = glassMaterial
        wheels.push(
            carModel.getObjectByName('wheel_fl'),
            carModel.getObjectByName('wheel_fr'),
            carModel.getObjectByName('wheel_rl'),
            carModel.getObjectByName('wheel_rr')
        )
        scene.add(carModel)
    })

    // RENDER LOOP
    function renderFrame() {
        const now   = performance.now()
        const delta = Math.min((now - lastFrameTime) / 1000, 0.05)
        lastFrameTime = now

        // Auto-rotate after 5s of inactivity
        if (now - lastUserInteraction > 5000) {
            targetAzimuth += delta * 0.35

            if (panControl) {
                const degrees = THREE.MathUtils.euclideanModulo(
                    THREE.MathUtils.radToDeg(targetAzimuth), 360
                )
                const rounded = Math.round(degrees)
                panControl.value = String(rounded)
                // ── sync the readout span too ──
                if (panVal) panVal.textContent = rounded + '°'
            }
        }

        currentAzimuth  = THREE.MathUtils.damp(currentAzimuth,  targetAzimuth,  8, delta)
        currentDistance = THREE.MathUtils.damp(currentDistance, targetDistance, 8, delta)

        applyCameraTargets()

        const time = -performance.now() / 1000
        wheels.forEach(wheel => { wheel.rotation.x = time * Math.PI * 2 })

        renderer.render(scene, camera)
    }

    function animate() {
        if (disposed) return
        if (!isVisible) { frameId = null; return }
        renderFrame()
        frameId = requestAnimationFrame(animate)
    }

    function startAnimation() {
        if (frameId || disposed) return
        frameId = requestAnimationFrame(animate)
    }

    startAnimation()

    // RESIZE
    const resize = () => {
        const { width, height } = getSize()
        camera.aspect = width / height
        camera.updateProjectionMatrix()
        renderer.setSize(width, height)
        if (!isVisible) renderFrame()
    }
    window.addEventListener('resize', resize)

    const observer = new IntersectionObserver(([entry]) => {
        isVisible = entry.isIntersecting
        if (isVisible) startAnimation()
    }, { threshold: 0.05 })
    observer.observe(container)

    // CLEANUP
    const cleanup = () => {
        disposed = true
        if (frameId) cancelAnimationFrame(frameId)
        observer.disconnect()
        window.removeEventListener('resize', resize)
        controls.dispose()
        unbindSliderControls()
        dracoLoader.dispose()
        scene.traverse(object => {
            if (!object.isMesh) return
            object.geometry?.dispose()
            const mats = Array.isArray(object.material) ? object.material : [object.material]
            mats.forEach(m => m?.dispose())
        })
        scene.environment?.dispose()
        renderer.dispose()
        renderer.domElement.remove()
        delete container.dataset.initialized
    }

    container.viewerCleanup = cleanup
    document.addEventListener('livewire:navigating', cleanup, { once: true })
}