import LogOutInButton from '../components/LogOutInButton'

export default function Home () {
  return (
    <div className='wrapper'>
      <h1 className='greeting'>Hey! Try logging in to see your Steam information</h1>
      <LogOutInButton login />

      <style jsx>
        {`
          .wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            width: 100%;
          }
          .greeting {
            text-align: center;
            padding-bottom: 30px;
            color: white;
          }
        `}
      </style>
    </div>
  )
}
