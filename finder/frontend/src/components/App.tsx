import '../styles/App.css'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Finder from './Finder'
import Results from './Results';

function App() {
  return (
    <Router>
      <Routes>
          <Route path="/" element={<Finder />} />
          <Route path="/results" element={<Results />} />
      </Routes>
    </Router>
  )
}

export default App
