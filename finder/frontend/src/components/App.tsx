import '../styles/App.css'
import { BrowserRouter as Router, Routes, Route } from 'react-router-dom';
import Finder from './Finder'
import Results from './Results';
import ResultDetails from './ResultsDetails';

function App() {
  return (
    <Router>
      <Routes>
          <Route path="/" element={<Finder />} />
          <Route path="/results" element={<Results />} />
          <Route path="/details/:id" element={<ResultDetails />} />
      </Routes>
    </Router>
  )
}

export default App
