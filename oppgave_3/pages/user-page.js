import Avatar from '../components/Avatar'
import LogOutInButton from '../components/LogOutInButton'

export default function UserPage ({ user }) {
  return (
    <div className='wrapper'>
      <h1 className='greeting'>Welcome, {user.displayName} !</h1>
      <Avatar
        size={100}
        src={user.photos[2].value}
        style={{ marginBottom: '30px' }}
      />
      <LogOutInButton />

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

export async function getServerSideProps ({ req, res }) {
  const user = req.user ?? false
  if (!user) {
    res.redirect('/')
  }
  return { props: { user } }
}
