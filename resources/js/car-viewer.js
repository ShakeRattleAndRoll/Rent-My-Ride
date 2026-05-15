import * as THREE from 'three'

import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls.js'

import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader.js'

import { DRACOLoader } from 'three/examples/jsm/loaders/DRACOLoader.js'

import { RGBELoader } from 'three/examples/jsm/loaders/RGBELoader.js'

document.addEventListener('livewire:navigated', initCarViewer)
document.addEventListener('DOMContentLoaded', initCarViewer)

function initCarViewer() {

    const container = document.getElementById('car-viewer')

    if (!container) return

    if (container.dataset.initialized) return

    container.dataset.initialized = true

    let camera
    let scene
    let renderer
    let controls

    const wheels = []

    // SCENE
    scene = new THREE.Scene()

    scene.fog = new THREE.Fog(0xf5f9ff, 10, 30)

    // CAMERA
    camera = new THREE.PerspectiveCamera(
        40,
        window.innerWidth / window.innerHeight,
        0.1,
        100
    )

    camera.position.set(4.25, 1.4, -4.5)

    // RENDERER
    renderer = new THREE.WebGLRenderer({
        antialias: true,
        alpha: true
    })

    renderer.setPixelRatio(window.devicePixelRatio)

    renderer.setSize(window.innerWidth, window.innerHeight)

    renderer.toneMapping = THREE.ACESFilmicToneMapping

    renderer.toneMappingExposure = 0.85

    renderer.outputColorSpace = THREE.SRGBColorSpace

    container.appendChild(renderer.domElement)

    // CONTROLS
    controls = new OrbitControls(camera, renderer.domElement)

    controls.enableDamping = true

    controls.maxDistance = 9

    controls.maxPolarAngle = THREE.MathUtils.degToRad(90)

    controls.target.set(0, 0.5, 0)

    controls.autoRotate = true

    controls.autoRotateSpeed = 1.5

    // HDRI
    new RGBELoader().load('/hdri/autoshop_01_4k.hdr', (texture) => {

        texture.mapping = THREE.EquirectangularReflectionMapping

        scene.environment = texture
    })

    // BACKGROUND HDRI
    new RGBELoader().load(
        '/hdri/qwantani_moon_noon_puresky_4k.hdr',

        (bgTexture) => {

            bgTexture.mapping =
                THREE.EquirectangularReflectionMapping

            // USED ONLY FOR BACKGROUND
            scene.background = bgTexture

            scene.backgroundIntensity = 0.45

            scene.backgroundBlurriness = 0.15

        }
    )

    // MATERIALS
    const bodyMaterial = new THREE.MeshPhysicalMaterial({
        color: 0xff0000,
        metalness: 1.0,
        roughness: 0.5,
        clearcoat: 1.0,
        clearcoatRoughness: 0.03
    })

    const detailsMaterial = new THREE.MeshStandardMaterial({
        color: 0xffffff,
        metalness: 1.0,
        roughness: 0.5
    })

    const glassMaterial = new THREE.MeshPhysicalMaterial({
        color: 0xffffff,
        metalness: 0.25,
        roughness: 0,
        transmission: 1.0
    })

    // SHADOW
    const shadow = new THREE.TextureLoader().load(
        '/models/ferrari_ao.png'
    )

    // DRACO
    const dracoLoader = new DRACOLoader()

    dracoLoader.setDecoderPath(
        'https://www.gstatic.com/draco/v1/decoders/'
    )

    // GLTF
    const loader = new GLTFLoader()

    loader.setDRACOLoader(dracoLoader)

    loader.load('/models/ferrari.glb', (gltf) => {

        const carModel = gltf.scene.children[0]

        carModel.getObjectByName('body').material = bodyMaterial

        carModel.getObjectByName('rim_fl').material = detailsMaterial
        carModel.getObjectByName('rim_fr').material = detailsMaterial
        carModel.getObjectByName('rim_rr').material = detailsMaterial
        carModel.getObjectByName('rim_rl').material = detailsMaterial

        carModel.getObjectByName('trim').material = detailsMaterial

        carModel.getObjectByName('glass').material = glassMaterial

        wheels.push(
            carModel.getObjectByName('wheel_fl'),
            carModel.getObjectByName('wheel_fr'),
            carModel.getObjectByName('wheel_rl'),
            carModel.getObjectByName('wheel_rr')
        )

        // SHADOW PLANE
        const mesh = new THREE.Mesh(
            new THREE.PlaneGeometry(0 * 4, 1.3 * 4),
            new THREE.MeshBasicMaterial({
                map: shadow,
                blending: THREE.MultiplyBlending,
                transparent: true
            })
        )

        mesh.rotation.x = -Math.PI / 2

        mesh.renderOrder = 2

        carModel.add(mesh)

        scene.add(carModel)
    })

    // ANIMATION
    function animate() {

        requestAnimationFrame(animate)

        controls.update()

        const time = -performance.now() / 1000

        wheels.forEach((wheel) => {

            wheel.rotation.x = time * Math.PI * 2
        })

        renderer.render(scene, camera)
    }

    animate()

    // RESIZE
    window.addEventListener('resize', () => {

        camera.aspect = window.innerWidth / window.innerHeight

        camera.updateProjectionMatrix()

        renderer.setSize(
            window.innerWidth,
            window.innerHeight
        )
    })
}