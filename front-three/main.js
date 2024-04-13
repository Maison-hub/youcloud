import * as THREE from 'three'
import { OrbitControls } from 'three/examples/jsm/controls/OrbitControls'
import { STLLoader } from 'three/examples/jsm/loaders/STLLoader'
import { GLTFLoader } from 'three/examples/jsm/loaders/GLTFLoader'


let cameraDistance = 4;
const aspectRatio = 16 / 9;

const material = new THREE.MeshPhysicalMaterial({
  color: 0xfff6ed,
  metalness: 0.1,
  roughness: 0.7,
});

const files = document.getElementsByClassName('file');

for(let filediv of files) { 

  const filename = filediv.getAttribute('data-file');
  const extension = filediv.getAttribute('data-extention');

  const previewDiv = filediv.querySelector('.preview');
  if (extension === 'stl') {
      const scene = creteScene();
      const camera = createCamera(aspectRatio, cameraDistance);
      const renderer = new THREE.WebGLRenderer()
      renderer.setSize(previewDiv.clientWidth, previewDiv.clientHeight)
      previewDiv.appendChild(renderer.domElement)

      const controls = new OrbitControls(camera, renderer.domElement)
      controls.enableDamping = true

      addStl(filename, scene, material, render(), camera)

      render()
      animate();
      function animate() {
        requestAnimationFrame(animate);
        controls.update();
        render();
      }
  
      function render() {
        renderer.render(scene, camera)
      }
  }

  if (extension === 'glb') {
      const scene = creteScene();
      const camera = createCamera(aspectRatio, 4);
      const renderer = new THREE.WebGLRenderer()
      renderer.setSize(previewDiv.clientWidth, previewDiv.clientHeight)
      previewDiv.appendChild(renderer.domElement)

      const controls = new OrbitControls(camera, renderer.domElement)
      controls.enableDamping = true
      addGlb(filename, scene, material, render())
      animate();
      function animate() {
        requestAnimationFrame(animate);
        controls.update();
        render();
      }
  
      function render() {
        renderer.render(scene, camera)
      }
  }
}

function addStl(filename, scene, material, render, camera) {
  const loader = new STLLoader()
  loader.load(filename, function (geometry) {
    const mesh = new THREE.Mesh(geometry, material);
    mesh.scale.set(1, 1, 1);
    mesh.position.set(0, 0, 0);
    render;
    scene.add(mesh);

    const positions = mesh.geometry.attributes.position.array;
    let minX = Infinity, minY = Infinity, minZ = Infinity;
    let maxX = -Infinity, maxY = -Infinity, maxZ = -Infinity;
    for (let i = 0; i < positions.length; i += 3) {
      const x = positions[i];
      const y = positions[i + 1];
      const z = positions[i + 2];

      minX = Math.min(minX, x);
      minY = Math.min(minY, y);
      minZ = Math.min(minZ, z);

      maxX = Math.max(maxX, x);
      maxY = Math.max(maxY, y);
      maxZ = Math.max(maxZ, z);
    }
    const sizeX = maxX - minX;
    const sizeY = maxY - minY;
    const sizeZ = maxZ - minZ;
    
    const maxDim = Math.max(sizeX, sizeY, sizeZ);

    camera.position.z = maxDim * 2;
    camera.position.x = 2
    camera.position.y = 2

    return;
  });
}

function addGlb(filename, scene, material, render) {
  const gltfloader = new GLTFLoader()
  gltfloader.load(filename, function (gltf) {
    scene.add(gltf.scene);
  });
}

function creteScene(){
  const scene = new THREE.Scene()
  scene.add(new THREE.AxesHelper(40))

  const light = new THREE.SpotLight()
  light.position.set(20, 20, 20)
  light.castShadow = true
  light.intensity = 100
  light.target.position.set(0, 0, 0)
  scene.add(light)

  const ambilight = new THREE.AmbientLight( 0xfffffff, 1)
  scene.add( ambilight )

  return scene
}

function createCamera(aspectRatio, distance){
  const camera = new THREE.PerspectiveCamera(
    75,
    aspectRatio,
    0.1,
    1000
  )

  camera.position.z = distance

  camera.lookAt(new THREE.Vector3(0, 0, 0));

  return camera
}

